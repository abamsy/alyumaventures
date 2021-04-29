<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Validation\Rule;

class Products extends Component
{
    public $name;
    public $modelId;
    public $modalVisibility = false;

    protected $listeners = ['delete'];

    public function rules(){
        return [
            'name' => ['required',
                    Rule::unique('products')->ignore($this->modelId)],
        ];
    } 

    public function createModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'modelId']);
        $this->modalVisibility = true;
    }

    public function modelData()
    {
        return [
            'name' => $this->name,
        ];
    }

    public function create()
    {
        $this->validate();

        Product::create($this->modelData());

        $this->modalVisibility = false; 

        $this->dispatchBrowserEvent('swal:modal',[
            'type' => 'success',
            'title' => 'Product created successfully',
            'text' => '',
        ]);

        $this->reset(['name',]);
    }

    public function loadModal()
    {
        $data = Product::find($this->modelId);
        $this->name = $data->name;
    }

    public function editModal($id)
    {
        $this->resetValidation();
        $this->reset(['name', 'modelId']);
        $this->modelId = $id;
        $this->modalVisibility = true;
        $this->loadModal();
    }

    public function update()
    {
        $this->validate();

        Product::find($this->modelId)->update($this->modelData());

        $this->modalVisibility = false;

        $this->dispatchBrowserEvent('swal:modal',[
            'type' => 'success',
            'title' => 'Product updated successfully',
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
        $product = Product::find($id);

        if ( $product->allocations()->count()) {
            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'error',
                'title' => 'Product has allocation and cannot be deleted',
                'text' => '',
            ]);
        }
        else
        {
            $product->delete();
            $this->dispatchBrowserEvent('swal:modal',[
            'type' => 'success',
            'title' => 'Product deleted successfully',
            'text' => '',
        ]);
        }
    }
    
    public function render()
    {
        return view('livewire.products', [
            'products' => Product::paginate(10)
        ]);
    }
}
