<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Allocation;
use App\Models\Share;
use App\Models\Plant;
use Illuminate\Validation\Rule;

class AllocationShares extends Component
{
    public Allocation $allocation;

    public $modalVisibility = false;
    public $modelId;
    public $plant_id;
    public $quantity;

    protected $listeners = ['delete'];

    public function rules ()
    {
        return [
            
            'plant_id' => 'required',            
            'quantity' => 'required|integer|min:0',            
        ];
    }

    public function createModal()
    {
        $this->resetValidation();
        $this->reset(['plant_id', 'quantity', 'modelId']);
        $this->modalVisibility = true;
    }

    public function modelData()
    {
        return [
            'allocation_id' => $this->allocation->id,
            'plant_id' => $this->plant_id,
            'quantity' => $this->quantity,
        ];
    }

    public function create()
    {
        $this->validate();

        $aq = $this->allocation->quantity;
        $sm = $this->allocation->shares->sum('quantity');
        $dq = $this->quantity;
        $balance = $aq -  $sm;
        
        if ( $balance < $dq )
        {
            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'error',
                'title' => 'Allocated Quantity exceeded, balance is ' .$balance. 'MT',
                'text' => '',
            ]); 
        }
        else
        {
            $share = Share::create($this->modelData());

            $this->modalVisibility = false; 

            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'success',
                'title' => 'Share added successfully',
                'text' => '',
            ]);

            $this->reset([ 'plant_id', 'quantity']); 
        }  
    }

    public function editModal($id)
    {
        $this->resetValidation();
        $this->reset(['plant_id', 'quantity', 'modelId']);
        $this->modelId = $id;
        $this->modalVisibility = true;
        $this->loadModal();
    }

    public function loadModal()
    {
        $data = Share::find($this->modelId);
        $this->plant_id = $data->plant_id;
        $this->quantity = $data->quantity;
        
    }

    public function update()
    {
        $this->validate();

        $aq = $this->allocation->quantity;
        $sm = $this->allocation->shares->sum('quantity');
        $dq = $this->quantity;
        $balance = $aq -  $sm;
        
        if ( $balance < $dq )
        {
            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'error',
                'title' => 'You cannot update the quantity by more than ' .$balance. 'MT',
                'text' => '',
            ]); 
        }
        else
        {
            Share::find($this->modelId)->update($this->modelData());

            $this->modalVisibility = false;

            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'success',
                'title' => 'Share updated successfully',
                'text' => '',
            ]);
        }
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
        $share = Share::find($id);
        
        if ( $share->waybills()->count()) {
            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'error',
                'title' => 'Share has waybill and cannot be deleted',
                'text' => '',
            ]);
        }
        else
        {
        
            $share->delete();

            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'success',
                'title' => 'Share deleted successfully',
                'text' => '',
            ]);
        }
        
    }

    public function render()
    {
        return view('livewire.allocation-shares', [
            'allocation' => $this->allocation,
            'shares' => Share::where('allocation_id', $this->allocation->id)->paginate(10),
            'plants' => Plant::all()
        ]);
    }
}
