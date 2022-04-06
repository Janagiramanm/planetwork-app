

     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
  
</x-slot>
@if($detailMap)
            @include('livewire.dashboard.detailMap')
@else

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
<div class="flex">
        <div   class="w-full mt-2 mr-1 ml-1 "> 
        <form  class="w-full max-w-6xl ml-10">
               
                    <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                                    <x-jet-label for="user_id" value="{{ __('Employee') }}" />
                                    <select id="user_id" wire:model.defer="user_id"   class="block mt-1 w-4/5 p-2  bg-gray-200" name="user_id">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                                <option value="{{ $user->user->id }}">
                                                        {{ ucfirst($user->user->name) }}  
                                                </option>
                                              
                                    @endforeach
                                   </select>
                                   @error('user_id') <span class="font-mono text-xs text-red-700">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-1/5">
                                <x-jet-label for="from_date" value="{{ __('From Date') }}" />
                                <x-datepicker wire:model.defer="from_date" id="from_date" :error="'from_date'" name="from_date" />
                            </div>
                            <div class="w-1/5">
                                <x-jet-label for="to_date" value="{{ __('To Date') }}" />
                                <x-datepicker wire:model.defer="to_date" id="from_date" :error="'to_date'" name="to_date" />
                            </div>
                          
                            <div class="w-1/5">
                                 <x-jet-button  class="bg-orange-500 hover:bg-orange-700  mt-4" >
                                        Search
                                 </x-jet-button>

                                 <x-jet-button  class="bg-orange-500 hover:bg-orange-700  mt-4" wire:click="backToDashboard()">
                                        Reset
                                 </x-jet-button> 
                            </div>
                            
                    </div>
               
                </form>
               
           
                <div class="w-full " id="map"></div>
                <div id="infowindow-content">
                        <span id="place-name" class="title"></span><br />
                        <span id="place-address"></span>
                </div>    
         
        </div>
      
<style>
      #map{
            height: 600px; 
            width: 100%; 
      }
</style> 
@push('scripts') 
<script>
$('document').ready(function(){
        
          setTimeout(function(){
                var locations = @php echo $this->latLong;  @endphp;
                 var lt = @php echo $this->lat;  @endphp;
                 var ln = @php echo $this->lng;  @endphp;

                 const geocoder = new google.maps.Geocoder();
                 const apikey = "@php echo env('GOOGLEMAPAPI') @endphp";
                //const infowindow = new google.maps.InfoWindow();
               // var infowindow = [];
               
                var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 10,
                        center: new google.maps.LatLng(13.00232321,77.9899200),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                        });

                  

                        var marker, i; 
                       //var address=["madurai","chennai"];
                       var infowindow = [];
                      
                   
                        for (i = 0; i < locations.length; i++) { 

                           
                              
                               var details = locations[i].details;
                              
                               const user_id = `${locations[i].user_id}`;
                               const date = `${locations[i].date}`;
                               const k = `${i}`;
                              

                                       $.get({ url: `https://maps.googleapis.com/maps/api/geocode/json?latlng=${locations[i].lat},${locations[i].lng}&sensor=false&key=${apikey}`,
                                                        success(res) {
                                                        if (geocoder) {
                                                          
                                                                geocoder.geocode({
                                                                'address': res.results[0].formatted_address,
                                                                                                            
                                                                }, function(results, status) {
                                                                        
                                                                       
                                                                                if (status == google.maps.GeocoderStatus.OK) {
                                                                                        if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                                                                                                map.setCenter(results[0].geometry.location);
                                                                                              
                                                                                                var infowindow = new google.maps.InfoWindow({
                                                                                                        content: locations[k].details+'<br><b>' + results[0].formatted_address + '</b>',
                                                                                                        size: new google.maps.Size(150, 50)
                                                                                                });
                        
                                                                                                var marker = new google.maps.Marker({
                                                                                                position: results[0].geometry.location,
                                                                                                map: map,
                                                                                                // content: details+ results[0].formatted_address
                                                                                                });
                                                                                                google.maps.event.addListener(marker, 'mouseover', function() {
                                                                                                infowindow.open(map, marker);
                                                                                                });
                        
                                                                                                google.maps.event.addListener(marker, 'mouseout', function() {
                                                                                                                infowindow.close();
                                                                                                });
                        
                                                                                                google.maps.event.addListener(marker, 'click', function(e) {
                                                                                                        Livewire.emit('getDetailPath',  user_id, date);
                                                                                                });
                
                                                                                    
                                                                                        } else {
                                                                                             alert("No results found");
                                                                                        }
                                                                                } else {
                                                                                        alert("Geocode was not successful for the following reason: " + status);
                                                                                }
                                                                        
                                                                     
                                                                });
                                                        }
                                               
                                                  }});
                                // console.log(address);
                                // var marker = new google.maps.Marker({
                                //                 // position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
                                //                 position: address,
                                //                 map: map,
                                //                 title: locations[i][3],
                                // });

                                //         var infowindow = new google.maps.InfoWindow({
                                //                 content: locations[i].details,
                                //                 maxWidth: 160
                                //         });
                                        
                                
                                //        google.maps.event.addListener(marker, 'mouseout', function() {
                                //         infowindow.close();
                                //         });

                                //         google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
                                //                 return function() {
                                //                 // close all the other infowindows that opened on load
                                //                 google.maps.event.trigger(map, 'click')
                                                
                                //                 infowindow.setContent(locations[i].details);
                                //                 infowindow.open(map, marker);
                                //                 }
                                //         })(marker, i));

                      
                               
                        //        $.get({ 
                                        
                        //                 url: `https://maps.googleapis.com/maps/api/geocode/json?latlng=${locations[i][1]},${locations[i][2]}&sensor=false&key=${apikey}`,
                        //                 data: {'userId':user_id,'date':date},
                        //                 success(res) {
                        //                 if (geocoder) {
                        //                       //console.log(data);
                        //                       //  const urlParams = new URLSearchParams();
                        //                 //       var urlParams = new URL(url);
                        //                 //       // var udetails = url.searchParams.get("details");
                        //                 //       var udetails = urlParams.get('latlng');
                        //                       console.log(userId);
                                             
                        //                         geocoder.geocode({
                        //                         'address': res.results[0].formatted_address,
                                                                                            
                        //                         }, function(results, status) {
                        //                                 // console.log(data.userId);
                        //                                 if (status == google.maps.GeocoderStatus.OK) {
                        //                                         if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                        //                                                 map.setCenter(results[0].geometry.location);

                        //                                                 var infowindow = new google.maps.InfoWindow({
                        //                                                         content: details+'<br><b>' + results[0].formatted_address + '</b>',
                        //                                                         size: new google.maps.Size(150, 50)
                        //                                                 });

                        //                                                 var marker = new google.maps.Marker({
                        //                                                 position: results[0].geometry.location,
                        //                                                 map: map,
                        //                                                 // content: details+ results[0].formatted_address
                        //                                                 });
                        //                                                 google.maps.event.addListener(marker, 'mouseover', function() {
                        //                                                        infowindow.open(map, marker);
                        //                                                 });

                        //                                                 google.maps.event.addListener(marker, 'mouseout', function() {
                        //                                                                 infowindow.close();
                        //                                                 });

                        //                                                   google.maps.event.addListener(marker, 'click', function(e) {
                        //                                                         Livewire.emit('getDetailPath',  user_id, date);
                        //                                                 });

                        //                                                 //details = null;
                        //                                                 delete details;

                        //                                         } else {
                        //                                         alert("No results found");
                        //                                         }
                        //                                 } else {
                        //                                         alert("Geocode was not successful for the following reason: " + status);
                        //                                 }
                        //                         });
                        //                 }
                               
                        //           }});

                       
                       
                        }
           },500)




                         
  });

   
</script>
@endpush  
@endif 


  



