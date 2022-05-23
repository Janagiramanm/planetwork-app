
            <div class="w-full">
                @if($employee)
                         <div> Name: {{ $employee->name }}</div> 
                         <div> Emp Code: {{ $employee->employeeDetail->emp_code}}</div> 
                         <div> Mobile: {{ $employee->mobile }}</div> 
                         <div> Date of Join: {{ $employee->employeeDetail->date_of_join }}</div> 
                @endif
            </div>
            <div>
                
            <table class="table-fixed w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class=" py-2">Date</th>
                                    <th class=" py-2">Customer</th>
                                    <th class=" py-2">Job</th>                                   
                                    <th class=" py-2">From</th>
                                    <th class=" py-2">To</th>
                                    <th class=" py-2">Travel Allowance</th>
                                </tr>   
                            </thead>
                            <tbody>
                               
                             @if($travelResult)
                                               
                                        @foreach($travelResult as $key => $value)
                                                <tr >
                                                    <td class="border px-4 py-2">{{ $value['date'] }}</td>
                                                    <td class="border px-4 py-2"><a href="/customers?id={{ $value['customer_id'] }}">
                                                        @if($value->job->customer->customer_type == 'BUSINESS')
                                                        {{ $value->job->customer->company_name }}
                                                        @else 
                                                        {{ $value->job->customer->first_name }} {{ $value->job->customer->last_name }}
                                                        @endif
                                                    </a>
                                                     </td>
                                                    <td class="border px-4 py-2">{{ $value->job->task->name }} </td>
                                                    <td class="border px-4 py-2">{{ $value->user->employeeDetail->address }} </td>
                                                    <td class="border px-4 py-2">{{ $value['to_address'] }} </td>
                                                    <td class="border px-4 py-2"></td>
                                
                                                    
                                                </tr>
                                                
                                            @endforeach
                                                    
                                  <tr>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                     
                                  </tr>
                                @else
                                  <tr><td colspan="5">No Records Found</td></tr>
                                 @endif
                            </tbody>
            </table>
            </div>
            

