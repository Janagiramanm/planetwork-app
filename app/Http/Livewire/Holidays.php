<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Holiday;

class Holidays extends Component
{
    public $updateMode, $createMode = false;
    public $confirmingItemDeletion, $name,$date;
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
            'name' => 'required',
            
        ]);
        Holiday::create([
            'date' => $this->date,
            'description' => $this->name,
                    
        ]);
        $this->createMode = false;
        $this->resetInput();

    }
    public function edit($id){
        $this->updateMode = true;
        $this->holiday_id = $id;
        $holiday= Holiday::where('id', $id)->first();
        $this->date = $holiday->date;
        $this->name = $holiday->description;
    }

    public function update(){
        $this->error = true;

        $this->validate([
            'date' => 'required',
            'name' => 'required',
            
        ]);

        if ($this->holiday_id) {
            $holiday = Holiday::find($this->holiday_id);
           
            $holiday->update([
                'date' => $this->date,
                'description' => $this->name,
                
            ]);

            $this->updateMode = false;
            //session()->flash('message', 'Users Updated Successfully.');
            $this->resetInput();

        }
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
