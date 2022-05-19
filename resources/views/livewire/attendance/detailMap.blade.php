


        <div   class="w-full mt-2 mr-1 ml-1 "> 
              <div class="details-section mt-10 mb-5">
                    <div class="flex ">
                          Name :<span class="font-sans md:font-serif text-sm mt-1 ml-3"> {{ $this->user_name }} </span>
                    </div>
                    <div >
                      Date :<span class="font-sans md:font-serif text-sm mt-1 ml-3"> {{ $this->date_display }} </span> </div>
                    <div>Distance in Km : <span class="font-sans md:font-serif text-sm mt-1 ml-3">{{ $this->distance }} </span> </div>
                    <div class="flex ">From :
                    <span class="float-none font-sans md:font-serif text-sm mt-1 ml-3"> {{ $this->from_address }} </span>
                    </div> 
                    <div class="flex ">To : 
                        <span class="font-sans md:font-serif text-sm mt-1 ml-3"> {{ $this->to_address }} </span>
                    </div>
                </div>
                @if(!$mapPath)
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
                @endif
                @if($mapPath)
                     @include('livewire.attendance.map')
                @endif
                <button wire:click="getDetailMapData" id="map-view-btn" class="hidden"></button>
         
        </div>   
        <script>
          $('document').ready(function(){

            $('#map-view-btn').click();
               
          });
  </script>
      

