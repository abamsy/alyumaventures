<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Plant;
use Illuminate\Validation\Rule;

class Plants extends Component
{
    public $name;
    public $state; 
    public $contact_person;
    public $phone;
    public $modalVisibility = false;
    public $modelId;

    protected $listeners = ['delete'];

    public function rules ()
    {
        return [
            'name' => ['required', 'min:6',
                        Rule::unique('plants')->ignore($this->modelId)],
            'state' => 'required',
            'contact_person' => 'required',
            'phone' => 'required',
        ];
    }

    
    public function createModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'state', 'contact_person', 'phone', 'modelId']);
        $this->modalVisibility = true;
    }

    public function modelData()
    {
        return [
            'name' => $this->name,
            'state' => $this->state,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
        ];
    }

    public function create()
    {
        $this->validate();

        $plant = Plant::create($this->modelData());

        $this->modalVisibility = false; 

        $this->dispatchBrowserEvent('swal:modal',[
            'type' => 'success',
            'title' => 'Blending plant added successfully',
            'text' => '',
        ]);

        $this->reset(['name', 'state', 'contact_person', 'phone']);
    }

    public function editModal($id)
    {
        $this->resetValidation();
        $this->reset(['name', 'state', 'contact_person', 'phone', 'modelId']);
        $this->modelId = $id;
        $this->modalVisibility = true;
        $this->loadModal();
    }

    public function loadModal()
    {
        $data = Plant::find($this->modelId);
        $this->name = $data->name;
        $this->state = $data->state;
        $this->contact_person = $data->contact_person;
        $this->phone = $data->phone;
    }

    public function update()
    {
        $this->validate();

        Plant::find($this->modelId)->update($this->modelData());

        $this->modalVisibility = false;

        $this->dispatchBrowserEvent('swal:modal',[
            'type' => 'success',
            'title' => 'Blending plant updated successfully',
            'text' => '',
        ]);

        $this->reset(['name', 'state', 'contact_person', 'phone']);
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
        $plant = Plant::find($id);

        if ( $plant->shares()->count()) {
            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'error',
                'title' => 'Blending plant has share and cannot be deleted',
                'text' => '',
            ]);
        }
        else
        {        
            $plant->delete();

            $this->dispatchBrowserEvent('swal:modal',[
            'type' => 'success',
            'title' => 'Blending plant deleted successfully',
            'text' => '',
        ]);
   
        } 
    }

    public function render()
    {
        return view('livewire.plants', [
            'plants' => Plant::paginate(10)
        ]);
    }
}
