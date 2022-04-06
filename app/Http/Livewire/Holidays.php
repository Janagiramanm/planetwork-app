<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Holiday;

class Holidays extends Component
{
    public $updateMode, $createMode = false;
    public $confirmingItemDeletion;
    public function render()
    {
        $this->holidays = Holiday::all();
        return view('livewire.holidays.list');
    }

    public function create(){
        $this->createMode = true;
        $this->addMore = true;
        $this->show = true;
        $this->error = false;
       // return view('livewire.holidays.create');
    }

    public function view(){
        $this->createMode = false;
        $this->updateMode = false;
        $this->resetInput();
    }

    public function confirmItemDeletion( $id) 
    {
        $this->confirmingItemDeletion = $id;
    }
 
    public function deleteItem( Customer $customer) 
    {
        $customer->delete();
        $this->confirmingItemDeletion = false;
        session()->flash('message', 'Item Deleted Successfully');
    }

    public function store(){
        $this->validate([
            'date' => 'required',
            'description' => 'required',
            
        ]);
        Holiday::create([
            'date' => $this->date,
            'description' => $this->description,
                    
        ]);
        $this->createMode = false;
        $this->resetInput();

    }

    private function resetInput()
    {
        $this->description = null;
        $this->date = null;
        $this->resetValidation();
        $this->render();
    }
}
