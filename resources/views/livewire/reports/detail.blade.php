<div class="py-12" wire:loading.remove>
            <x-jet-button  class="bg-orange-500 hover:bg-orange-700  mt-4 mb-4 float-right" wire:click="goback()">
                        Back
            </x-jet-button> 
            <table class="min-w-full leading-normal">
                             <tr>
                                <th  class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"> 
                                    Date / Time 
                                </th>
                                <th  class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"> 
                                Locations </th>
                                <th  class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"> 
                                Status </th>
                             </tr>
                        
                         @foreach($result as $key => $value)
                               <tr>
                                   
                                   <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $value['date'] }} <br> {{ $value['time'] }} </td>
                                   <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $value['address'] }}</td>
                                   <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $value['status'] }} </td>
                               </tr>
                         @endforeach
            </table>
</div>
