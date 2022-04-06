
        <h2 class="font-semibold text-xl text-gray-800 leading-tight my-6 ml-10">
            @if($createMode)  {{ __('Assign New Job') }}
            @elseif($updateMode) {{ __('Edit Job') }}
            @endif
        </h2>
        <br>
        <x-jet-secondary-button wire:click="view()" class=" float-right bg-orange-500 hover:bg-gray-300 hover:text-white-100 px-4 py-2 -my-20">
           Jobs
        </x-jet-button>
        
        <div>
        <form  class="w-full max-w-6xl ml-10">
               <div class="flex flex-wrap -mx-3 mb-6">
                      <div class="w-full px-3">
                          <div class="block">
                              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                    Customer Type
                              </label>
                              <label class="inline-flex items-center">
                                <input type="radio" class="form-radio" name="customer_type" value="BUSINESS" checked wire:model="customer_type" wire:click="$set('show', true)">
                                <span class="ml-2">Business</span>
                              </label>
                              <label class="inline-flex items-center">
                                <input type="radio" class="form-radio" name="customer_type" value="INDIVIDUAL" checked wire:model="customer_type" wire:click="$set('show', false)">
                                <span class="ml-2">Individual</span>
                              </label>
                              <br>
                              @error('customer_type') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          </div>
                      </div>
                </div>
               
                @if($show)
                <div class="flex flex-wrap -mx-3 mb-6">
                          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                    <x-jet-label for="customer_id" value="{{ __('Customer') }}" />
                                    <select id="customer_id" wire:model="customer_id"  wire:change="address" class="block mt-1 w-4/5 p-2  bg-gray-200" name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach ($business as $customer)
                                                <option value="{{ $customer->id }}">
                                                        {{ ucfirst($customer->company_name) }}
                                                </option>
                                    @endforeach
                                   </select>
                                   @error('customer_id') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          </div>
                          
                          
                </div>
                @else
                    <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                    <x-jet-label for="customer_id" value="{{ __('Customer') }}" />
                                    <select id="customer_id" wire:model="customer_id" wire:change="address"  class="block mt-1 w-4/5 p-2  bg-gray-200" name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach ($individual as $customer)
                                                <option value="{{ $customer->id }}">
                                                        {{ ucfirst($customer->first_name) }} {{ $customer->last_name}}
                                                </option>
                                    @endforeach
                                   </select>
                                   @error('customer_id') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                            </div>
                            
                    </div>
                @endif

                @if($showLocation)
                    <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                    <x-jet-label for="address" value="{{ __('Select Location') }}" />
                                    @foreach($locations as $key => $location)
                                      
                                        <div class="border-2  md:w-4/5  m-1 border-gray-600 p-4" for="address"> 
                                           <input type="radio" class="float-right mt-4 h-8 w-8" wire:model="address" value="{{$location->id}}" name="address" ></input>
                                            <div>
                                               Branch - {{ $location->branch }}
                                                </div>
                                            <div>
                                                City - {{ $location->city->name }}
                                            </div>
                                            <div>
                                                Address - {{ $location->address }}
                                            </div>
                                        </div>
                                        
                                    @endforeach
                                    @error('address') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                                  
                            </div>
                    </div>
                @endif
             
                <div class="flex flex-wrap -mx-3 mb-6">
                          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                    <x-jet-label for="task_id" value="{{ __('Task') }}" />
                                    <select id="task_id" wire:model="task_id"  class="block mt-1 w-4/5 p-2  bg-gray-200" name="task_id">
                                    <option value="">Select Task</option>
                                    @foreach ($tasks as $task)
                                                <option value="{{ $task->id }}">
                                                        {{ $task->name }}
                                                </option>
                                    @endforeach
                                   </select>
                                   @error('task_id') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <x-jet-label for="task_id" value="{{ __('Assign Employee') }}" />
                                <x-select2 id="category-dropdown" wire:model="user_id"  class="block mt-1 w-4/5 p-2  bg-gray-200"  name="user_id">
                                    <option value="">Select Employe</option>
                                    @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                        {{ $employee->name }}
                                                </option>
                                    @endforeach
                                </x-select2>
                                @error('user_id') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                        </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <x-datepicker wire:model="date" id="date" :error="'date'" name="date" />
                                <br>
                                @error('date') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                                </div>
                </div>
               

                
               
                @if($createMode)
                  <x-jet-button wire:click.prevent="store()" class="bg-orange-500 hover:bg-orange-700  mt-4">
                        Save
                  </x-jet-button>
                @elseif($updateMode)
                      <x-jet-button wire:click.prevent="update()" class="bg-orange-500 hover:bg-orange-700  mt-4">
                            Update
                      </x-jet-button>
                @endif

                </div>

                </div>
</form>




@include('livewire/input/adminInput-css')
@include('livewire/input/adminInput-js')


