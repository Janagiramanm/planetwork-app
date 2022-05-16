
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
                                    <th class=" py-2">Day</th>
                                    <th class=" py-2">Login</th>                                   
                                    <th class=" py-2">Logout</th>
                                    <th class=" py-2">Working Hours</th>
                                    <th class=" py-2">Details</th>
                                    
                                </tr>   
                            </thead>
                            <tbody>
                               
                             @if($details)
                                @php 
                                    $total_hrs = 0 ;
                                  $i=0;
                                @endphp 
                                   @foreach($monthInDays as $key1 => $value1)
                                                 @php
                                                     $default = 'yes';
                                                   
                                                      @endphp
                                                      @foreach($details as $key => $value)
                                                        @php 
                                                        $date_val =  date('d-m-Y',strtotime($value->date));
                                                      if($value1['date'] ==  $date_val){
                                                        // echo $value->logout.'=='.$value->login.'<br>';
                                                        $value->logout = ($value->logout != '') ? $value->logout : $value->login;
                                                        $working_hours =  round(round(abs(strtotime($value->logout) - strtotime($value->login)) / 60, 2) / 60,2);
                                                            $default = 'no';
                                                        @endphp 
                                                
                                                        <tr style="background:{{ $value1['color'] }};">
                                                            <td class="border px-4 py-2">{{ $value1['date'] }}</td>
                                                            <td class="border px-4 py-2">{{ $value1['day'] }}</td>
                                                            <td class="border px-4 py-2">{{ date('H:i',strtotime($value->login)) }}</td>
                                                            <td class="border px-4 py-2">{{  date('H:i',strtotime($value->logout)) }}</td>
                                                            <td class="border px-4 py-2">{{ $working_hours }}</td>
                                                            <td class="border px-4 py-2"><a href="#" wire:click="detailView()">Detail Report</span></td>
                                                        </tr>
                                                        @php 
                                                         $total_hrs += $working_hours;
                                                        }
                                                       
                                                        @endphp 
                                                       
                                                @endforeach
                                                       @if($default=='yes')
                                                 
                                                        <tr style="background:{{ $value1['color'] }};">
                                                                <td class="border px-4 py-2">{{ $value1['date'] }}</td>
                                                                <td class="border px-4 py-2">{{ $value1['day'] }}</td>
                                                            @if($value1['color'] == 'red')
                                                                <td class="border px-4 py-2 text-white" colspan="3" >Weekend</td>
                                                            @elseif($value1['color'] == 'blue')
                                                                <td class="border px-4 py-2 text-white" colspan="3" > <b>{{ $value1['holiday'] }}</b></td>
                                                            @else
                                                                <td class="border px-4 py-2">--</td>
                                                                <td class="border px-4 py-2">--</td>
                                                                <td class="border px-4 py-2">--</td>
                                                                
                                                            @endif

                                                            <td class="border px-4 py-2">--</td>
                                                        </tr>
                                                        @endif
                                                  
                                                     @php 
                                                          $i++;
                                                     @endphp 
                                   @endforeach
                                      
                               
                                
                                  <tr>
                                      <td></td>
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

