
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customers') }}
        </h2>
  
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
        @if($updateMode)
            @include('livewire.roles.create')
            @elseif($createMode)
                @include('livewire.roles.create')
        @else
            <table class="table-fixed w-full">
                          
                            <x-jet-secondary-button wire:click="create()" class=" float-right bg-orange-500 hover:bg-gray-300 hover:text-white-100 px-4 py-2 my-6">
                                        Add New Role
                            </x-jet-button>
                    
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 w-20">No.</th>
                                    <th class="px-4 py-2">Name</th>
                                    <th class="px-4 py-2">Description</th>                                   
                                    <th class="px-4 py-2">Action</th>
                                </tr>   
                            </thead>
                            <tbody>
                            @php $no = 1; @endphp
                                @foreach($roles as $role)
                                <tr>
                                    <td class="border px-4 py-2">{{ $no++ }}</td>
                                    <td class="border px-4 py-2">{{ ucfirst($role->name) }}</td>
                                    <td class="border px-4 py-2">{{ $role->description }}</td>
                                    <td class="border px-4 py-2">
                                    @if($role->name != 'administrator')
                                    <x-jet-button wire:click="edit( {{ $role->id}})" class="bg-orange-500 hover:bg-orange-700 m-1 w-20">
                                        Edit
                                    </x-jet-button>
                                    @endif
                                    
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
            </table>
            @endif


            <x-jet-confirmation-modal wire:model="confirmingItemDeletion">
                    <x-slot name="title">
                        {{ __('Delete Item') }}
                    </x-slot>
            
                    <x-slot name="content">
                        {{ __('Are you sure you want to delete Item? ') }}
                    </x-slot>
            
                    <x-slot name="footer">
                        <x-jet-secondary-button wire:click="$set('confirmingItemDeletion', false)" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-jet-secondary-button>
            
                        <x-jet-danger-button class="ml-2" wire:click="deleteItem({{ $confirmingItemDeletion }})" wire:loading.attr="disabled">
                            {{ __('Delete') }}
                        </x-jet-danger-button>
                    </x-slot>
            </x-jet-confirmation-modal>

</div>
</div>
</div>
