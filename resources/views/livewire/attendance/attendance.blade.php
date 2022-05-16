<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance') }}
        </h2>
  
</x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
        <div class="flex flex-wrap -mx-3 mb-6 px-4">
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
                                 <x-jet-button  class="bg-orange-500 hover:bg-orange-700  mt-4" wire:click="render()" >
                                        Filter
                                 </x-jet-button>

                                 <x-jet-button  class="bg-orange-500 hover:bg-orange-700  mt-4" wire:click="backPage()">
                                        Reset
                                 </x-jet-button> 
                            </div>
                            
                    </div>
                    @if($detailView)
                        @include('livewire.attendance.detailView')
                    @else
                       <table class="table-fixed w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class=" py-2">Name</th>
                                    <th class=" py-2">Actual Hours</th>                                   
                                    <th class=" py-2">Total Hours</th>
                                    <th class=" py-2">Over / Less Time ( Hours )</th>
                                    <th class=" py-2">Details</th>
                                    
                                </tr>   
                            </thead>
                            <tbody>
                             @if(!$result->isEmpty())
                                 @foreach($result as $key => $value)
                                        @php  
                                            $total_hours = $total_hours;
                                            $actual_hours = round($value->minutes / 60,2) ;
                                            $ot = $actual_hours - $total_hours ;
                                            $ot = ($actual_hours > $total_hours) ? '+'.$ot:$ot; 

                                        @endphp 
                                        <tr>
                                            <td class="border px-4 py-2">{{ $value->user->name }}</td>
                                            <td class="border px-4 py-2">{{ $actual_hours }}</td>
                                            <td class="border px-4 py-2">{{ $total_hours }}</td>
                                            <td class="border px-4 py-2">{{ $ot }}</td>
                                            <td class="border px-4 py-2"><a href="#" wire:click="detailView({{ $value->user_id }})">Detail Report</span></td>
                                        </tr>
                                 @endforeach
                                @else
                                  <tr><td colspan="5">No Records Found</td></tr>
                                 @endif
                            </tbody>
                     </table>
                     @endif

</div>
</div>
</div>