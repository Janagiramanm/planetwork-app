
            <div>
                @if($employee)
                   
                         <div> Name: {{ $employee->name }}</div> 
                         <div> Emp Code: {{ $employee->employeeDetail->emp_code}}</div> 
                         <div>Mobile: {{ $employee->mobile }}</div> 
                         <div>Date of Join: {{ $employee->employeeDetail->date_of_join }}</div> 
                         
                    
                @endif
            </div>
            <table class="table-fixed w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class=" py-2">Date</th>
                                    <th class=" py-2">Login</th>                                   
                                    <th class=" py-2">Logout</th>
                                    <th class=" py-2">Working Hours</th>
                                    <th class=" py-2">Details</th>
                                    
                                </tr>   
                            </thead>
                            <tbody>
                                @php 
                                $total_hrs = 0 ;
                                @endphp 
                             @if(!$details->isEmpty())
                                 @foreach($details as $key => $value)
                                          @php 
                                            echo $value->logout.'=='.$value->login.'<br>';
                                           $value->login = $value->login != '' ? $value->login : $value->logout;
                                           $working_hours =  round(abs(strtotime($value->logout) - strtotime($value->login)) / 60, 2) / 60;
                                          @endphp 
                                        
                                        <tr>
                                            <td class="border px-4 py-2">{{ date('d-m-Y',strtotime($value->date)) }}</td>
                                            <td class="border px-4 py-2">{{ date('H:i',strtotime($value->login)) }}</td>
                                            <td class="border px-4 py-2">{{  date('H:i',strtotime($value->logout)) }}</td>
                                            <td class="border px-4 py-2">{{ $working_hours }}</td>
                                            <td class="border px-4 py-2"><a href="#" wire:click="detailView()">Detail Report</span></td>
                                        </tr>
                                        @php 
                                           $total_hrs += $working_hours;
                                        @endphp 
                                 @endforeach
                                  <tr>
                                      <td></td>
                                      <td></td>
                                      <td class="border px-4 py-2">Total Hours</td>
                                      <td class="border px-4 py-2">{{ $total_hrs  }}</td>
                                  </tr>
                                @else
                                  <tr><td colspan="5">No Records Found</td></tr>
                                 @endif
                            </tbody>
            </table>

