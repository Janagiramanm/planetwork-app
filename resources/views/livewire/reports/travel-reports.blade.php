

     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
  
</x-slot>


<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
<div class="flex">
       
        <div   class="w-full mt-2 mr-1 ml-1 "> 
        
                @if($travelDetail)
                    @include('livewire.reports.travel-detail')
                @else
                    <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                                    <x-jet-label for="user_id" value="{{ __('Employee') }}" />
                                    <select id="user_id" wire:model.defer="user_id"   class="block mt-1 w-4/5 p-2  bg-gray-200" name="user_id">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                                <option value="{{ $user->user_id }}">
                                                        {{ ucfirst($user->user->name) }}  
                                                </option>
                                              
                                    @endforeach
                                   </select>
                                   @error('user_id') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-1/6">
                                <x-jet-label for="from_date" value="{{ __('Month') }}" />
                                <select id="month" wire:model.defer="month"   class="block mt-1 w-4/5 p-2  bg-gray-200" name="month">
                                    <option value="">Select Month</option>
                                    @foreach ($this->months as $key => $value)
                                          <option value="{{$value}}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <!-- <x-datepicker wire:model.defer="from_date" id="from_date" :error="'from_date'" name="from_date" />
                                @error('from_date') <br>    <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror -->
                            </div>
                            <div class="w-1/6">
                                <x-jet-label for="to_date" value="{{ __('Year') }}" />
                                <select id="year" wire:model.defer="year"   class="block mt-1 w-4/5 p-2  bg-gray-200" name="year">
                                    <option value="">Select Year</option>
                                    @foreach ($this->years as $key => $year)
                                          <option value="{{$year}}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                          
                            <div class="w-1/5">
                                <x-jet-label for="to_date" value="{{ __('Search') }}" />
                                <input type="text" wire:model.defer="title"  name="title" value="" class="mt-1" />
                              
                            </div>
                          
                            <div class="w-1/5">
                                 <x-jet-button  class="bg-orange-500 hover:bg-orange-700  mt-4" wire:click="render()" >
                                        Generate
                                 </x-jet-button>

                                 <x-jet-button  class="bg-orange-500 hover:bg-orange-700  mt-4" wire:click="backToReports()">
                                        Reset
                                 </x-jet-button> 
                            </div>
                            
                    </div>
                    <div wire:loading class="flex ml-24 justify-center items-center">
                       <div class="flex justify-center items-center">
                        <div
                            class="
                            animate-spin
                            rounded-full
                            h-20
                            w-20
                            border-t-2 border-b-2 border-purple-500
                            "
                        ></div>
                        </div>  
                    </div>

                    <div wire:loading.remove>
                         <table class="min-w-full leading-normal">
                         <tr class="bg-gray-100">
                                
                                    <th class="py-2">Employees</th>
                                    <th class="py-2">Travel distance</th> 
                                    <th class="py-2">Total Allowance ( Rs )</th>  
                                    <th class="py-2">View Details</th>  
                                     
                                </tr>
                                @if(!$result)
                                   <tr><td colspan="6" class="text-center">No Result Found</td></tr>
                                @else
                                    
                                    @foreach($result as $key => $value)
                                        <tr >
                                            <td class=" border px-5 py-5 border-b border-gray-200 bg-white text-sm"><a href="/users?id={{ $value['user_id'] }}">{{ $value->user->name }}</a> </td>  
                                            
                                
                                            <td class=" border px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                        
                                                         {{ $value['travel_distance'] }} km 
                                                        
                                               
                                            </td>
                                            <td class="border"></td> 
                                            <td class=" border px-5 py-5 border-b border-gray-200 bg-white text-sm cursor-pointer" wire:click="viewTravelDetails({{$value['user_id']}})"> View Details</td> 
                                                                                                                                                                                                                   
                                        </tr>
                                    @endforeach
                                @endif
                         </table>
                    </div>
                    @endif
        </div>
      
<style>
      #map{
            height: 600px; 
            width: 100%; 
      }
</style> 




  



