

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
                            <div class="w-1/5">
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
                            <div class="w-1/5">
                                <x-jet-label for="to_date" value="{{ __('Year') }}" />
                                <select id="year" wire:model.defer="year"   class="block mt-1 w-4/5 p-2  bg-gray-200" name="year">
                                    <option value="">Select Year</option>
                                    @foreach ($this->years as $key => $year)
                                          <option value="{{$year}}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                          
                            <div class="w-1/5">
                                 <x-jet-button  class="bg-orange-500 hover:bg-orange-700  mt-4" wire:click="generateWorkReport()" >
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

                    @if($viewPath)
                          @include('livewire.reports.path')
                    @elseif($detailReport)
                          @include('livewire.reports.detail')
                   @elseif($reportShow)
                    <div wire:loading.remove>
                         <table class="min-w-full leading-normal">
                             <tr>
                                <th class="px-4 py-2 w-20">Date</th>
                                    <th class="px-4 py-2">Employees</th>
                                    <th class="px-4 py-2">Customer Name</th>
                                    <th class="px-4 py-2">Jobs</th>
                                    <th class="px-4 py-2">Status</th>   
                                    <th class="px-4 py-2">From</th>
                                    <th class="px-4 py-2">To</th>    
                                    <th class="px-4 py-2">Travel distance</th>  
                                    <th class="px-4 py-2">SR No</th>
                                    <th class="px-4 py-2">Geofence</th>
                               
                             </tr>
                                @if(!$result)
                                   <tr><td colspan="6" class="text-center">No Result Found</td></tr>
                                @else

                                    @foreach($result as $key => $value)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $value['date'] }}  </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><a href="/users?id={{ $value['user_id'] }}">{{ $value['user_name'] }}</a> </td>  
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><a href="/customers?id={{ $value['customer_id'] }}">{{ $value['customer_name'] }}</a> </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $value['job_name'] }} </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            @php 
                                                $status = $value['job_status'];
                                                    if($status){
                                                        foreach($status as $emp_status){
                                                            if($emp_status->user_id == $value['user_id']){
                                                                echo $emp_status->job_status;
                                                            }
                                                        }
                                                    }
                                            @endphp 
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $value['from_address'] }}
                                                <br><b><label> Start Time: {{ $value['start'] }}<lable></b>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $value['to_address'] }}
                                                <br><b><label> End Time: {{ $value['end'] }}<lable></b> 
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                         <x-jet-button wire:click="viewPath({{$value['user_id']}},{{$value['start']}}, {{$value['end']}})" class="bg-orange-500 hover:bg-orange-700 m-1 w-20">
                                                         {{ $value['travel_distance'] }} km 
                                                         </x-jet-button>
                                                <!-- <button wire:click="viewPath($value['user_id'],$value['start'], $value['end'])" >{{ $value['travel_distance'] }} km </button> -->
                                            </td>  
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><a href="/jobs?id={{ $value['job_id'] }}">{{ $value['sr_no'] }}</a></td>   
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                @if($value['is_reached'] == 'true')
                                                    <img width="32" height="32" src="/images/tick_mark.png" />
                                                @else
                                                    <img width="32" height="32" src="/images/wrong.png" />
                                                @endif
                                          </td>                                                                                                                                                                         
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




  



