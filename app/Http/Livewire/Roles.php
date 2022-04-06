<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Role;

class Roles extends Component
{
    public $updateMode,$createMode = false;
    public $roles, $name, $description, $confirmingItemDeletion;
    public $show = true;

    public function render()
    {
        $this->roles = Role::all();
        return view('livewire.roles.list');
    }

    public function create(){
        $this->createMode = true;
        return view('livewire.roles.create');
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
        Role::create([
            'name' => $this->name,
            'description' => $this->description          
        ]);
        $this->createMode = false;
        $this->resetInput();

    }

    public function edit($id){
        $this->updateMode = true;
        $this->role_id = $id;
        $role = Role::where('id', $id)->first();
        $this->name = $role->name;
        $this->description = $role->description;
    }

    public function update(){

        $this->error = true;

        $this->validate([
            'name' => 'required'
        ]);

        if ($this->role_id) {
            $role = Role::find($this->role_id);
           
            $role->update([
                'name' => $this->name,
                'description' => $this->description,
                
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
 
    public function deleteItem( Customer $customer) 
    {
        $customer->delete();
        $this->confirmingItemDeletion = false;
        session()->flash('message', 'Item Deleted Successfully');
    }

    private function resetInput()
    {
        $this->name = null;
        $this->description = null;      
        $this->render();
    }

}
