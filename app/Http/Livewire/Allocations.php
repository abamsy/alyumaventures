<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Allocation;
use App\Models\Product;
use Illuminate\Validation\Rule;

class Allocations extends Component
{
    public $date;
    public $product_id;
    public $quantity;
    public $modalVisibility = false;
    public $modelId;

    protected $listeners = ['delete'];

    public function createModal()
    {
        $this->resetValidation();
        $this->reset(['date', 'product_id', 'quantity','modelId']);
        $this->modalVisibility = true;
    }

    public function rules ()
    {
        return [
            'date' => 'required|date',
            'product_id' => 'required',   
            'quantity' => 'required|integer|min:0',             
        ];
    }

    public function modelData()
    {
        return [
            'date' => $this->date,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
        ];
    }

    public function create()
    {
        $this->validate();

        $allocation = Allocation::create($this->modelData());

        $this->modalVisibility = false; 

        $this->dispatchBrowserEvent('swal:modal',[
            'type' => 'success',
            'title' => 'Allocation added successfully',
            'text' => '',
        ]);

        $this->reset(['date', 'product_id', 'quantity']);
    }

    public function loadModal()
    {
        $data = Allocation::find($this->modelId);
        $this->date = $data->date;
        $this->product_id = $data->product_id;
        $this->quantity = $data->quantity;
    }

    public function editModal($id)
    {
        $this->resetValidation();
        $this->reset(['date', 'product_id', 'quantity', 'modelId']);
        $this->modelId = $id;
        $this->modalVisibility = true;
        $this->loadModal();
    }

    public function update()
    {
        $this->validate();

        Allocation::find($this->modelId)->update($this->modelData());

        $this->modalVisibility = false;

        $this->dispatchBrowserEvent('swal:modal',[
            'type' => 'success',
            'title' => 'Allocation updated successfully',
            'text' => '',
        ]);
    }

    public function deleteConfirm($id)
    {
        $this->dispatchBrowserEvent('swal:confirm',[
            'type' => 'warning',
            'title' => 'Are you sure?',
            'text' => '',
            'id' => $id,
        ]);
    }
    
    public function delete($id)
    {
        $allocation = Allocation::find($id);
        
        if ( $allocation->shares()->count()) {
            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'error',
                'title' => 'Allocation has share and cannot be deleted',
                'text' => '',
            ]);
        }
        else
        {
            $allocation->delete();

            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'success',
                'title' => 'Allocation deleted successfully',
                'text' => '',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.allocations', [
            'allocations' => Allocation::paginate(10),
            'products' => Product::all()
        ]);
    }
}
