<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\City;

class Cities extends Component
{
    public $updateMode,$createMode = false;
    public  $name, $confirmingItemDeletion;
    public $show = true;
    public function render()
    {
        $this->cities = City::all();
        return view('livewire.cities.list');
    }

    public function create(){
        $this->createMode = true;
        return view('livewire.cities.create');
    }

    public function view(){
        $this->createMode = false;
        $this->updateMode = false;
        $this->resetInput();
    }

    public function store(){
        $this->validate([
            'name' => 'required',
            
        ]);
        City::create([
            'name' => $this->name
                    
        ]);
        $this->createMode = false;
        $this->resetInput();

    }

    public function edit($id){
        $this->updateMode = true;
        $this->city_id = $id;
        $city = City::where('id', $id)->first();
        $this->name = $city->name;
       
    }

    public function update(){

        
        $this->validate([
            'name' => 'required'
        ]);

        if ($this->city_id) {
            $city = City::find($this->city_id);
           
            $city->update([
                'name' => $this->name
               
            ]);

            $this->updateMode = false;
            //session()->flash('message', 'Users Updated Successfully.');
            $this->resetInput();

        }
        $this->createMode = false;
        $this->resetInput();
    }

    public function confirmItemDeletion( $id) 
    {
        $this->confirmingItemDeletion = $id;
    }
 
    public function deleteItem( City $city) 
    {
        $city->delete();
        $this->confirmingItemDeletion = false;
        session()->flash('message', 'Item Deleted Successfully');
    }

    private function resetInput()
    {
        $this->name = null;
        $this->resetValidation();
        $this->render();
    }
}
