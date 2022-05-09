      <h2 class="font-semibold text-xl text-gray-800 leading-tight my-6 ml-10">
          @if($createMode)  {{ __('Add New User') }}
          @elseif($updateMode) {{ __('Edit User') }}
          @endif
      </h2>
      <br>
     
      
      <x-jet-secondary-button wire:click="view()" class=" float-right bg-orange-500 hover:bg-gray-300 hover:text-white-100 px-4 py-2 -my-20">
           Users 
      </x-jet-button>

      <form  class="w-full max-w-6xl ml-10 mr-10">

            <div class="flex">
                  <div class="md:w-1/2 m-2"> 
                           <x-jet-label for="role" value="{{ __('Role') }} " /> 
                            <select id="role" wire:model.defer="role"  class="block mt-1 w-4/5 p-2  bg-gray-200" name="role">
                              <option value="">Select Role</option>
                              @foreach ($roles as $role)
                                          <option value="{{ $role->id }}">
                                                {{ ucfirst($role->name) }}
                                          </option>
                              @endforeach

                           </select>
                             @error('role') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="name" value="{{ __('Designation') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="designation" type="text" placeholder="" wire:model.defer="designation">
                             @error('designation') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                  </div>

            </div>
            <div class="flex">
                  <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="emp_code" value="{{ __('Emp Code') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="emp_code" type="text" placeholder="" wire:model.defer="emp_code">
                             @error('emp_code') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                  </div>              
                  <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="name" value="{{ __('Full Name') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="name" type="text" placeholder="" wire:model.defer="name">
                             @error('name') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                  </div> 
            </div>
            <div class="flex">
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="mobile" value="{{ __('Mobile') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="mobile" type="text" placeholder="" wire:model.defer="mobile">
                             @error('mobile') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="email" value="{{ __('Email') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="email" type="text" placeholder="" wire:model.defer="email">
                             @error('email') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
            </div>
            <div class="flex">
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="imei" value="{{ __('IMEI') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="imei" type="text" placeholder="" wire:model.defer="imei">
                             @error('imei') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="city_id" value="{{ __('City') }}" />
                            <select id="city_id" wire:model.defer="city_id"  class="block mt-1 w-4/5 p-2  bg-gray-200" name="city_id">
                              <option value="">Select City</option>
                              @foreach ($cities as $city)
                                          <option value="{{ $city->id }}">
                                                {{ ucfirst($city->name) }}
                                          </option>
                              @endforeach
 
                           </select>
                           
                             @error('city') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
            </div>
            <div class="flex">
                  <div class="md:w-1/2  m-2 mr-3">
                                <x-jet-label for="date_of_join" value="{{ __('Date of Joining') }}" />
                                <x-datepicker wire:model.defer="date_of_join" id="date" :error="'date_of_join'" name="date_of_join" />
                                <br>
                                @error('date_of_join') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                  </div>
                 
            </div>
            <div class="flex">
                 <div wire:ignore class="md:w-1/2 m-2 mr-3 @if($createMode) hidden @endif " id="address-div"> 
                            <span><b>Address</b></span><br>
                            <span id="address-section">{{ $this->address }}</span><br>
                            <span><b>Latitude</b></span> :  <span id="lat-section"> {{ $this->latitude }}</span>
                            <span><b>Longitude</b></span> : <span id="lng-section"> {{ $this->longitude }} </span>
                          
                 </div>
                 <div class="md:w-1/2 m-2 mr-3 hidden" id="address-div1">  
                              
                             @error('address') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div>
                     
            </div>
         
            <div class="flex">
            
                  <div wire:ignore  class="w-full m-2 mr-28 "> 
                      <div  style="display: none">
                              <input
                              id="pac-input"
                              class="controls mt-5 w-1/2"
                              type="text"
                              placeholder="Enter Address"
                              value="@if($updateMode) {{ $this->address }} @endif"
                              />
                        </div>
                       
                        <div id="map"></div>
                        <div id="infowindow-content">
                              <span id="place-name" class="title"></span><br />
                              <span id="place-address"></span>
                        </div>      
                  </div>
                 
            </div>

            <div class="flex w-full">
                  <h2 class="font-semibold ml-1 mt-5 mb-5">Pay Details</h2>
            </div>
            <div class="flex">
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="basic_pay" value="{{ __('Basic Pay') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-basic-pay" 
                              name="basic_pay" type="text" placeholder="" wire:model.defer="basic_pay">
                             @error('basic_pay') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="hra" value="{{ __('HRA') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-hra" 
                              name="hra" type="text" placeholder="" wire:model.defer="hra">
                             @error('hra') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
            </div>
            <div class="flex">
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="conveyance" value="{{ __('Conveyance') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-conveyance" 
                              name="conveyance" type="text" placeholder="" wire:model.defer="conveyance">
                             @error('conveyance') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="gratuity_pay" value="{{ __('Gratuity Pay') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-gratuity_pay" 
                              name="gratuity_pay" type="text" placeholder="" wire:model.defer="gratuity_pay">
                             @error('gratuity_pay') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
            </div>
            <div class="flex">
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="special_allowance" value="{{ __('Special Allowance') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-special_allowance" 
                              name="special_allowance" type="text" placeholder="" wire:model.defer="special_allowance">
                             @error('special_allowance') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="variable_incentive" value="{{ __('Variable Incentive') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-variable_incentive" 
                              name="variable_incentive" type="text" placeholder="" wire:model.defer="variable_incentive">
                             @error('variable_incentive') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
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
                  <x-jet-danger-button wire:click="confirmingUserDeletion( {{ $user_id}})" wire:loading.attr="disabled" class="m-1 w-20">
                                           Delete
                   </x-jet-danger-button>
            @endif
           
             
      </form>
      <!-- Modal -->
      <x-jet-confirmation-modal wire:model="confirmingUserDeletion">
                    <x-slot name="title">
                        {{ __('Delete Item') }}
                    </x-slot>
            
                    <x-slot name="content">
                        {{ __('Are you sure you want to delete Item? ') }}
                    </x-slot>
            
                    <x-slot name="footer">
                        <x-jet-secondary-button wire:click="$set('confirmingUserDeletion', false)" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-jet-secondary-button>
            
                        <x-jet-danger-button class="ml-2" wire:click="deleteItem({{ $confirmingUserDeletion }})" wire:loading.attr="disabled">
                            {{ __('Delete') }}
                        </x-jet-danger-button>
                    </x-slot>
            </x-jet-confirmation-modal>
       

      <style>
            #map{
                  height: 400px; 
            }
      </style>
     
      <script>
            userMap();
      </script>
     
   
     
      


 
     
        