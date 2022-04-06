

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
<div class="flex">
        <div   class="w-full mt-2 mr-1 ml-1 "> 
             
               <x-jet-secondary-button wire:click="back()" class=" float-right bg-orange-500 hover:bg-gray-300 hover:text-white-100 px-4 py-2 my-6">
                                        Back
                </x-jet-button>
                <div class="details-section mt-10 mb-5">
                    <div class="flex ">
                          Name :<span class="font-sans md:font-serif text-sm mt-1 ml-3"> {{ $this->user_name }} </span>
                    </div>
                    <div >
                      Date :<span class="font-sans md:font-serif text-sm mt-1 ml-3"> {{ $this->date }} </span> </div>
                    <div>Distance in Km : <span class="font-sans md:font-serif text-sm mt-1 ml-3">{{ $this->distance }} </span> </div>
                    <div class="flex ">From :
                    <span class="float-none font-sans md:font-serif text-sm mt-1 ml-3"> {{ $this->from_address }} </span>
                    </div> 
                    <div class="flex ">To : 
                        <span class="font-sans md:font-serif text-sm mt-1 ml-3"> {{ $this->to_address }} </span>
                    </div>
                </div>
                <div class="w-full " id="detail-map"></div>
                <div id="infowindow-detail">
                        <span id="place-name" class="title"></span><br />
                        <span id="place-address"></span>
                </div>    
         
        </div>
      
<style>
      #detail-map{
            height: 600px; 
            width: 100%; 
      }
</style> 
<script>
  $('document').ready(function(){

    
         
    detailMap();
    function detailMap() {
      
    var lt =  11.0168;
    var ln = 76.9558;
    
   
    var mapProp = {
      center: new google.maps.LatLng(11.0168, 76.9558),
      zoom: 5,
    };
    var map = new google.maps.Map(document.getElementById("detail-map"), mapProp);

    
    var goldenGatePosition = @php echo $this->reslatLong;  @endphp;
    var ideal_locations = @php echo $this->ideal_locations;  @endphp;
   

   
    var bounds = new google.maps.LatLngBounds();
    var lastplace = goldenGatePosition.length - 1 ;
    var distance =  getDistanceFromLatLonInKm(goldenGatePosition[0].lat,goldenGatePosition[0].lng,goldenGatePosition[lastplace].lat,goldenGatePosition[lastplace].lng);
    for (let i = 0; i < goldenGatePosition.length; i++) {
           console.log(goldenGatePosition[0]);
            var marker = new google.maps.Marker({ 
              position: goldenGatePosition[0],
              map: map,
              title: goldenGatePosition[i].lat+', '+goldenGatePosition[i].lng+', '+goldenGatePosition[i].time
              // title: 'Golden Gate Bridge'+' distance '+distance+' length '+lastplace
            });


            var marker = new google.maps.Marker({
              position: goldenGatePosition[lastplace],
              map: map,
              // title: 'Golden Gate Bridge'
              title: goldenGatePosition[lastplace].lat+', '+goldenGatePosition[lastplace].lng
            });
      
      bounds.extend(goldenGatePosition[i]);
    }
    //console.log(ideal_locations);

    for (let i=0; i <ideal_locations.length; i++){
            //console.log(ideal_locations[i]);
                  var marker = new google.maps.Marker({ 
                      position: ideal_locations[i],
                      map: map,
                      title: '',
                      icon:{
                          path: 'm 12,2.4000002 c -2.7802903,0 -5.9650002,1.5099999 -5.9650002,5.8299998 0,1.74375 1.1549213,3.264465 2.3551945,4.025812 1.2002732,0.761348 2.4458987,0.763328 2.6273057,2.474813 L 12,24 12.9825,14.68 c 0.179732,-1.704939 1.425357,-1.665423 2.626049,-2.424188 C 16.809241,11.497047 17.965,9.94 17.965,8.23 17.965,3.9100001 14.78029,2.4000002 12,2.4000002 Z',
                          fillColor: '#FFFF00',
                          fillOpacity: 1.0,
                          strokeColor: '#000000',
                          strokeWeight: 1,
                          scale: 2,
                          anchor: new google.maps.Point(12, 24),
                      },
                      // title: 'Golden Gate Bridge'+' distance '+distance+' length '+lastplace
                    });
            }
   
    var flightPath = new google.maps.Polyline({
      path: goldenGatePosition,
      geodesic: true,
      strokeColor: "#0000FF",
      strokeOpacity: 2.8,
      strokeWeight: 4,
      travelMode: google.maps.DirectionsTravelMode.DRIVING
    });
    flightPath.setMap(map);
    map.fitBounds(bounds);

    var infowindow = new google.maps.InfoWindow();
    var codeStr = ''

    google.maps.event.addListener(flightPath, 'mouseover', function(h) {
     var latlng=h.latLng;
     var needle = {
         minDistance: 9999999999, //silly high
         index: -1,
         latlng: null
     };
    //  flightPath.getPath().forEach(function(routePoint, index){
    //      var dist = google.maps.geometry.spherical.computeDistanceBetween(latlng, routePoint);
    //      if (dist < needle.minDistance){
    //         needle.minDistance = dist;
    //         needle.index = index;
    //         needle.latlng = routePoint;
    //      }
    //  });
     
     // The clicked point on the polyline
     var latLang = latlng.toString().replace(/[\(\)']+/g,'');
     var address =   getAddressByLatLong(latLang,latlng,infowindow, map);
    

 });
    
    }

    function getAddressByLatLong(latLng,latlng,infowindow, map){

                 const apikey = "@php echo env('GOOGLEMAPAPI') @endphp";
                 const geocoder = new google.maps.Geocoder();
                 var address = $.get({ url: `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latLng}&sensor=false&key=${apikey}`,
                              success(res) {
                              if (geocoder) {
                    
                                  geocoder.geocode({
                                    'address': res.results[0].formatted_address,
                                                                                
                                       }, function(results, status) {
                                          if (status == google.maps.GeocoderStatus.OK) {
                                                  if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                                                        // return results[0].formatted_address;
                                                         infowindow.setContent(results[0].formatted_address);
                                                         infowindow.setPosition(latlng);
                                                          infowindow.open(map)
                                                   
                                                          // map.setCenter(results[0].geometry.location);
                                                        
                                                          // var infowindow = new google.maps.InfoWindow({
                                                          //         content: locations[k].details+'<br><b>' + results[0].formatted_address + '</b>',
                                                          //         size: new google.maps.Size(150, 50)
                                                          // });

                                                          // var marker = new google.maps.Marker({
                                                          // position: results[0].geometry.location,
                                                          // map: map,
                                                          // // content: details+ results[0].formatted_address
                                                          // });
                                                          // google.maps.event.addListener(marker, 'mouseover', function() {
                                                          // infowindow.open(map, marker);
                                                          // });

                                                          // google.maps.event.addListener(marker, 'mouseout', function() {
                                                          //                 infowindow.close();
                                                          // });

                                                          // google.maps.event.addListener(marker, 'click', function(e) {
                                                          //         Livewire.emit('getDetailPath',  user_id, date);
                                                          // });

                                              
                                                  } else {
                                                        alert("No results found");
                                                  }
                                          } else {
                                                  alert("Geocode was not successful for the following reason: " + status);
                                          }
                                  
                                
                                  });
                              }
                                               
                          }});

              return address;          
    }
  

  function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
        var R = 6371; // Radius of the earth in km
        var dLat = deg2rad(lat2-lat1);  // deg2rad below
        var dLon = deg2rad(lon2-lon1); 
        var a = 
          Math.sin(dLat/2) * Math.sin(dLat/2) +
          Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
          Math.sin(dLon/2) * Math.sin(dLon/2)
          ; 
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        var d = R * c; // Distance in km
        return d;
      }
      
      function deg2rad(deg) {
        return deg * (Math.PI/180)
      }

});

   
</script>