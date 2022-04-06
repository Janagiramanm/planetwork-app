<h2 class="font-semibold text-xl text-gray-800 leading-tight my-6 ml-10">
            {{ __('Update Branch Location') }}
</h2>
<x-jet-secondary-button wire:click="edit({{ $this->customer_id }})" class=" float-right bg-orange-500 hover:bg-gray-300 hover:text-white-100 px-4 py-2 -my-10 ">
           Back
</x-jet-button>

<div class="w-full mr-12 ml-10">
                            <div class="w-1/2  ">
                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-branch-name">
                                      Branch
                                    </label>
                                    <input class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="grid-branch-name" 
                                       type="text" placeholder="" wire:model.defer="edit_branch" name="edit_branch">
                                    @error('edit_branch') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror

                                    <x-jet-label for="city.0" value="{{ __('City') }}" />
                                    <select id="city" wire:model.defer="edit_city"  class="block mt-1 w-full p-2 bg-gray-200" name="edit_city"> 
                                      <option value="">Select City</option>
                                      @foreach ($cities as $city)
                                                  <option value="{{ $city->id }}">
                                                        {{ ucfirst($city->name) }}
                                                  </option>
                                      @endforeach
                                    </select>
                                    @error('edit_city') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror

                                    <div wire:ignore class="md:w-1/2 m-2 mr-3  @if($createMode)  hidden @endif " id="address-div.0"> 
                                          <span><b>Address</b></span><br>
                                          <span id="cust-address-section"> {{ $this->edit_address }} </span><br>
                                          <span><b>Latitude</b></span> :  <span id="lat-section" data-lat="{{ $this->edit_lat }}"> {{ $this->edit_lat }} </span>
                                          <span><b>Longitude</b></span> : <span id="lng-section" data-lat="{{ $this->edit_lng }}"> {{ $this->edit_lng }} </span>
                                    </div>
                                    @error('edit_address') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                          
                            </div>

                            <div class="w-full mr-12">
                                        <div wire:ignore  class="w-full m-2 mr-28 "> 
                                                    <div wire:ignore style="display: block">
                                                        <input
                                                        id="edit-pac-input"
                                                        class="pac-input"
                                                        type="text"
                                                        placeholder="Enter Address"
                                                        value="@if($updateMode) {{ $this->address }} @endif"
                                                        data-val="{{$this->i}}"
                                                        />
                                                    </div>
                                                
                                                    <div   id="edit_map"></div>
                                                    <div wire:ignore id="infowindow-content">
                                                        <span id="edit-place-name" class="title"></span><br />
                                                        <span id="edit-place-address"></span>
                                                    </div>      
                                            </div>
                            </div>
                            <div class="w-full">
                                    <x-jet-button wire:click.prevent="updateLocation()" class="bg-orange-500 hover:bg-orange-700 ml-2">
                                                Update
                                          </x-jet-button>
                            </div>
                             
            
 </div>

 <style>
      #edit_map{
            height: 400px; 
            width: 600px;
      }
</style>     
<script>
      editBranchLocation();
</script>