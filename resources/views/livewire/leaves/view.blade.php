<h2 class="font-semibold text-xl text-gray-800 leading-tight my-6 ml-10">
            {{ __('Leave Request view') }}
</h2>
<x-jet-secondary-button wire:click="view()" class=" float-right bg-orange-500 hover:bg-gray-300 hover:text-white-100 px-4 py-2 -my-10 ">
           Leaves
</x-jet-button>
<form  class="w-full max-w-6xl ml-10 mr-10">

            <div class="flex">
                  <div class="md:w-1/6 m-2"> 
                            <x-jet-label for="ame " value="{{ __('Name') }}" />
                            
                  </div> 
                  <div class="md:w-1/2 m-2"> 
                            - {{ $user_name }}
                  </div>

            </div>
            <div class="flex">
                  <div class="md:w-1/6 m-2"> 
                            <x-jet-label for="name " value="{{ __('From Date as') }}" />
                            
                  </div> 
                  <div class="md:w-1/2 m-2"> 
                            - {{ date('d/m/Y',strtotime($from_date)) }}
                  </div>

            </div>
            <div class="flex">
                  <div class="md:w-1/6 m-2"> 
                            <x-jet-label for="ame " value="{{ __('To Date') }}" />
                  </div> 
                  <div class="md:w-1/2 m-2"> 
                            - {{ date('d/m/Y',strtotime($to_date))  }}
                  </div>
            </div>
            <div class="flex">
                  <div class="md:w-1/6 m-2"> 
                            <x-jet-label for="ame " value="{{ __('Requested days ') }}" />
                  </div> 
                  <div class="md:w-1/2 m-2"> 
                            - {{ $this->request_days }}
                  </div>
            </div>
         
            <div class="flex">
                  <div class="md:w-1/6 m-2"> 
                            <x-jet-label for="ame " value="{{ __('Leave Type') }}" />
                  </div> 
                  <div class="md:w-1/2 m-2"> 
                            - {{ $leave_type }}
                  </div>
            </div>
            <div class="flex">
                  <div class="md:w-1/6 m-2"> 
                            <x-jet-label for="ame " value="{{ __('Reason') }}" />
                  </div> 
                  <div class="md:w-1/2 m-2"> 
                            - {{ $reason }}
                  </div>
            </div>
            @if($this->status =='approved')
                  <div class="flex bg-green-500 mr-10">
                        <h2 class="m-5 "> Approved </h2>
                  </div>
            @endif

            @if($this->status =='modify-approved')
            <div class="flex bg-gray-200 mr-10">
                  <h2 class="m-5 ">Leave Status</h2>
            </div>
            
            <div class="flex">
                  <div class="md:w-1/6 m-2"> 
                            <x-jet-label for="ame " value="{{ __('Approved days ') }}" />
                  </div> 
                  <div class="md:w-1/2 m-2"> 
                            - {{ $this->approved_days }}
                  </div>
            </div>
            
            <div class="flex">
                  <div class="md:w-1/6 m-2"> 
                            <x-jet-label for="ame " value="{{ __('From Date ') }}" />
                  </div> 
                  <div class="md:w-1/2 m-2"> 
                            - {{ date('d/m/Y',strtotime($this->approved_from))  }}
                  </div>
            </div>
            <div class="flex">
                  <div class="md:w-1/6 m-2"> 
                            <x-jet-label for="ame " value="{{ __('To Date ') }}" />
                  </div> 
                  <div class="md:w-1/2 m-2"> 
                            - {{ date('d/m/Y',strtotime($this->approved_to))  }}
                  </div>
            </div>
            @endif
            @if($this->status =='rejected')
                  <div class="flex bg-red-500 mr-10">
                        <h2 class="m-5 ">{{ ucfirst($this->status) }}</h2>
                  </div>
                  <div class="flex">
               
                              <div class="md:w-1/2 m-2"> 
                                          <x-jet-label for="reject_reason" value="{{ __('Reason for Reject') }}" />
                                          <div class="mt-3">{{ $this->reject_reason  }}</div>
                              </div> 
                  </div>
            @endif
            @if($cancelMode)
            <div class="flex">
               
                 <div class="md:w-1/2 m-2"> 
                            <x-jet-label for="reject_reason" value="{{ __('Reason for Reject') }}" />
                            <input class="appearance-none block w-4/5 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4   leading-tight focus:outline-none focus:bg-white" id="grid-first-name" 
                              name="reject_reason" type="text" placeholder="" wire:model="reject_reason">
                             @error('reject_reason') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                 </div> 
            </div>
            @endif
            
           @if($updateMode && $this->status=='pending')
                  <x-jet-button wire:click.prevent="approve()" class="bg-orange-500 hover:bg-orange-700 ml-2 mt-5">
                        Approve 
                  </x-jet-button>
                  <x-jet-button wire:click.prevent="cancel()" class="bg-red-500 hover:bg-red-700 ml-2 mt-5">
                        Reject
                  </x-jet-button>
                  <x-jet-button wire:click.prevent="modify({{ $this->leave_id }})" class="bg-orange-500 hover:bg-orange-700 ml-2 mt-5">
                      Modify & Approve
                  </x-jet-button>
            @endif

</form>