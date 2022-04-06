
        <h2 class="font-semibold text-xl text-gray-800 leading-tight my-6 ml-10">
            @if($createMode)  {{ __('Add New Customer') }}
            @elseif($updateMode) {{ __('Edit Customer') }}
            @endif
        </h2>
        <br>
        <x-jet-secondary-button wire:click="view()" class=" float-right bg-orange-500 hover:bg-gray-300 hover:text-white-100 px-4 py-2 -my-20">
           Customers
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
                <div class="flex flex-wrap md:w-1/2 -mx-3 mb-6">
                          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                              Primary Contact
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4  leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                            name="first_name" type="text" placeholder="FIRST NAME" wire:model="first_name">
                              @error('first_name') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          </div>
                          <div class="w-full md:w-1/2 my-6">
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4  leading-tight focus:outline-none focus:bg-white focus:border-gray-500" 
                                id="grid-last-name" name="last_name" type="text" placeholder="LAST NAME" wire:model="last_name">
                                @error('last_name') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          </div>
                </div>
                @if($show)
                <div class="flex flex-wrap -mx-3 mb-6">
                          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                              Company Name
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="company_name" type="text" placeholder="" wire:model="company_name">
                             @error('company_name') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          </div>
                          
                </div>
                @endif
                <div class="flex flex-wrap -mx-3 mb-6">
                          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                              Email
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="customer_email" type="text" placeholder="" wire:model="customer_email">
                             @error('customer_email') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                              Phone
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="phone" type="text" placeholder="" wire:model="phone">
                             @error('phone') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                              Website
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="website" type="text" placeholder="" wire:model="website">
                             @error('website') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          </div>
                </div>

               
                <div class="add-input">
                    <div class="flex mr-12">
                      <div class="w-1/2  ">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                              Branch
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="branch.0" type="text" placeholder="" wire:model="branch.0">
                             @error('branch.0') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          

                            <x-jet-label for="role" value="{{ __('City') }}" />
                            <select id="city.0" wire:model="city.0"  class="block mt-1 w-full p-2 bg-gray-200" name="city">
                              <option value="">Select City</option>
                              @foreach ($cities as $city)
                                          <option value="{{ $city->id }}">
                                                {{ ucfirst($city->name) }}
                                          </option>
                              @endforeach

                           </select>
                             @error('city.0') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror

                             <div wire:ignore class="md:w-1/2 m-2 mr-3 @if($createMode)  @endif " id="address-div"> 
                                      <span><b>Address</b></span><br>
                                      <span id="cust-address-section.0">{{ $this->address }}</span><br>
                                      <span><b>Latitude</b></span> :  <span id="lat-section.0"> {{ $this->latitude }}</span>
                                      <span><b>Longitude</b></span> : <span id="lng-section.0"> {{ $this->longitude }} </span>
                                    
                              </div>
                               
                       </div>
                       <div class="w-1/2  ">
                            <span wire:click.prevent="add({{$i}})" class=" rounded-full h-7 w-7 mt-24 flex items-center justify-center bg-green-500 bg-orange-500 hover:bg-orange-700 ml-10 mt-5 ">
                                          +
                            </span>
                       </div>
                      
                                
                    </div>
                   
                </div>

                @foreach($locations as $key => $value)

                  <div class="add-input">
                      <hr class="mt-10 mb-5 ">
                      <div class="flex mr-12">
                        <div class="w-1/2  ">
                              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                Branch
                              </label>
                              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                                name="branch.{{ $value }}" type="text" placeholder="" wire:model="branch.{{ $value }}">
                              @error('branch.{{ $value }}') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror

                                <x-jet-label for="role" value="{{ __('City') }}" />
                                  <select id="city" wire:model="city.{{ $value }}"  class="block mt-1 w-full p-2 bg-gray-200" name="city.{{ $value }}">
                                      <option value="">Select City</option>
                                      @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">
                                                  {{ ucfirst($city->name) }}
                                            </option>
                                      @endforeach
                                  </select>
                                
                                  @error('city.{{ $value }}') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                              
                                  <span wire:click.prevent="remove({{$key}})" class=" rounded-full h-7 w-7 mt-24 flex items-center justify-center bg-red-500 bg-orange-500 hover:bg-orange-700 ml-10">
                                            -
                                  </span>
                                 
                      </div>
                      <div class="w-1/2  ">
                                 
                      </div>
     
                  </div>
                
              </div>
              @endforeach
                   

                  <div class="flex">
                          
                          <div wire:ignore  class="w-full m-2 mr-28 "> 
                                <div wire:ignore style="display: block">
                                      <input
                                      id="pac-input"
                                      class="controls"
                                      type="text"
                                      placeholder="Enter Address"
                                      value="@if($updateMode) {{ $this->address }} @endif"
                                      />
                                </div>
                              
                                <div  wire:ignore id="map"></div>
                                <div wire:ignore id="infowindow-content">
                                      <span id="place-name" class="title"></span><br />
                                      <span id="place-address"></span>
                                </div>      
                          </div>
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
</form>
</div>
<style>
      #map{
            height: 400px; 
      }
</style>     
<script>
      customerMap();
</script>

