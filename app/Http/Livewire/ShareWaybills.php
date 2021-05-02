<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Share;
use App\Models\Waybill;
use Illuminate\Validation\Rule;

class ShareWaybills extends Component
{
    public Share $share;

    public $modalVisibility = false;
    public $modelId;
    public $date;
    public $quantity;
    public $bags;
    public $driver;
    public $phone;
    public $truck;

    protected $listeners = ['delete'];

    public function rules ()
    {
        return [
            
            'date' => 'required|date',            
            'quantity' => 'required|integer|min:0', 
            'bags' => 'required|integer|min:0', 
            'driver' => 'required',
            'phone' => 'required',  
            'truck' => 'required'        
        ];
    }

    public function createModal()
    {
        $this->resetValidation();
        $this->reset(['date', 'quantity', 'bags', 'driver', 'phone', 'truck','modelId']);
        $this->modalVisibility = true;
    }

    public function modelData()
    {
        return [
            'share_id' => $this->share->id,
            'date' => $this->date,
            'quantity' => $this->quantity,
            'bags' => $this->bags,
            'driver' => $this->driver,
            'phone' => $this->phone,
            'truck' => $this->truck
        ];
    }

    public function create()
    {
        $this->validate();

        $aq = $this->share->quantity;
        $sm = $this->share->waybills->sum('quantity');
        $dq = $this->quantity;
        $balance = $aq -  $sm;

        if ( $balance < $dq )
        {
            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'error',
                'title' => 'Shared Quantity exceeded, balance is ' .$balance. 'MT',
                'text' => '',
            ]); 
        }
        else
        {
            $waybill = Waybill::create($this->modelData());

            $this->modalVisibility = false; 

            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'success',
                'title' => 'Waybill added successfully',
                'text' => '',
            ]);

            $this->reset(['date', 'quantity', 'bags', 'driver', 'phone', 'truck']);  
        } 
    }

    public function editModal($id)
    {
        $this->resetValidation();
        $this->reset(['date', 'quantity', 'bags', 'driver', 'phone', 'truck','modelId']);
        $this->modelId = $id;
        $this->modalVisibility = true;
        $this->loadModal();
    }

    public function loadModal()
    {
        $data = Waybill::find($this->modelId);
        $this->date = $data->date;
        $this->quantity = $data->quantity;
        $this->bags = $data->bags;
        $this->driver = $data->driver;
        $this->phone = $data->phone;
        $this->truck = $data->truck;
    }

    public function update()
    {
        $this->validate();

        $aq = $this->share->quantity;
        $sm = $this->share->waybills->sum('quantity');
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

            Waybill::find($this->modelId)->update($this->modelData());

            $this->modalVisibility = false;

            $this->dispatchBrowserEvent('swal:modal',[
                'type' => 'success',
                'title' => 'Waybill updated successfully',
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
        $waybill = Waybill::find($id);

        
        $waybill->delete();

        $this->dispatchBrowserEvent('swal:modal',[
            'type' => 'success',
            'title' => 'Waybill deleted successfully',
            'text' => '',
        ]);

        
    }

    public function render()
    {
        return view('livewire.share-waybills', [
            'share' => $this->share,
            'waybills' => Waybill::where('share_id', $this->share->id)->get()
        ]);
    }
}
