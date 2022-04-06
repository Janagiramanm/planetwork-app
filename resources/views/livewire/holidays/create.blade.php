<h2 class="font-semibold text-xl text-gray-800 leading-tight my-6 ml-10">
            {{ __('Add New Holiday') }}
</h2>
<x-jet-secondary-button wire:click="view()" class=" float-right bg-orange-500 hover:bg-gray-300 hover:text-white-100 px-4 py-2 -my-20 ">
           Holidays
      </x-jet-button>

      <form  class="w-full max-w-6xl ml-10 mr-10">

            <div class="flex">
            <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="ame " value="{{ __('Holiday Name') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="name" type="text" placeholder="" wire:model="name">
                             @error('name') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
            </div> 
          
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <x-datepicker wire:model="date" id="date" :error="'date'" name="date" />
                    <br>
                    @error('date') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
            </div>
            

            </div>
           
            @if($createMode)
                  <x-jet-button wire:click.prevent="store()" class="bg-orange-500 hover:bg-orange-700 ml-2">
                        Save
                  </x-jet-button>
            @elseif($updateMode)
                  <x-jet-button wire:click.prevent="update()" class="bg-orange-500 hover:bg-orange-700 ml-2">
                        Update
                  </x-jet-button>
            @endif

</form>