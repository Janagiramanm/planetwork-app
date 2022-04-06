// This sample requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script
// src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
function userMap() {
    const myLatlng = new google.maps.LatLng(12.972442,77.580643);
    const map = new google.maps.Map(document.getElementById("map"), {
      center: myLatlng,
      zoom: 13,
    });
    const input = document.getElementById("pac-input");
    const autocomplete = new google.maps.places.Autocomplete(input);
  
    autocomplete.bindTo("bounds", map);

    // Specify just the place data fields that you need.
    autocomplete.setFields(["place_id", "geometry", "name", "formatted_address"]);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  
    const infowindow = new google.maps.InfoWindow();
    const infowindowContent = document.getElementById("infowindow-content");
  
    infowindow.setContent(infowindowContent);
  
    var latitude = 23.786758526804636;
    var longitude = 90.39979934692383;
    var LatLng = new google.maps.LatLng(latitude, longitude);

    const geocoder = new google.maps.Geocoder();
    const marker = new google.maps.Marker({ 
      draggable: true,
       map: map ,
      });
      google.maps.event.addListener(marker, 'dragend', function(e) {
        displayPosition(this.getPosition());
      });
        
    marker.addListener("click", () => {
      infowindow.open(map, marker);
    });

    autocomplete.addListener("place_changed", () => {
      infowindow.close();
  
      const place = autocomplete.getPlace();
  
      if (!place.place_id) {
        return;
      }
  
      geocoder
        .geocode({ placeId: place.place_id })
        .then(({ results }) => {
          map.setZoom(16);
          map.setCenter(results[0].geometry.location);
         
          document.getElementById('address-div').style.display ='block'; 
          document.getElementById('address-div1').style.display ='block'; 
          document.getElementById('address-section').innerHTML = results[0].formatted_address;
          document.getElementById('lat-section').innerHTML = results[0].geometry.location.lat();
          document.getElementById('lng-section').innerHTML = results[0].geometry.location.lng();
        
         const marker = new google.maps.Marker({
            map: map,
            draggable: true,
            position: results[0].geometry.location
          });

          google.maps.event.addListener(marker, 'dragend', function() {

            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
           
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                  
                  document.getElementById('pac-input').value = results[0].formatted_address;
                  document.getElementById('address-section').innerHTML = results[0].formatted_address;
                  document.getElementById('lat-section').innerHTML = results[0].geometry.location.lat();
                  document.getElementById('lng-section').innerHTML = results[0].geometry.location.lng();
                  infowindow.setContent(results[0].formatted_address);
                  infowindow.open(map, marker);
                  Livewire.emit('getLatLngForInput', results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng());
                }
            }
            });
          });
          Livewire.emit('getLatLngForInput', results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng());
          marker.setVisible(true);
          infowindowContent.children["place-name"].textContent = place.name;
          infowindowContent.children["place-address"].textContent =
            results[0].formatted_address;
                    
          infowindow.open(map, marker);
        })
        .catch((e) => window.alert("Geocoder failed due to: " + e));

    });

    
}

// this is for customer form map 
function customerMap() {
  const myLatlng = new google.maps.LatLng(12.972442,77.580643);
  const map = new google.maps.Map(document.getElementById("map"), {
    center: myLatlng,
    zoom: 13,
  });
  const input = document.getElementById("pac-input");
  const autocomplete = new google.maps.places.Autocomplete(input);

  autocomplete.bindTo("bounds", map);

  // Specify just the place data fields that you need.
  autocomplete.setFields(["place_id", "geometry", "name", "formatted_address"]);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  const infowindow = new google.maps.InfoWindow();
  const infowindowContent = document.getElementById("infowindow-content");

  infowindow.setContent(infowindowContent);

  var latitude = 23.786758526804636;
  var longitude = 90.39979934692383;
  var LatLng = new google.maps.LatLng(latitude, longitude);

  const geocoder = new google.maps.Geocoder();
  const marker = new google.maps.Marker({ 
    draggable: true,
     map: map ,
    });
  
  autocomplete.addListener("place_changed", () => {
    infowindow.close();

    const place = autocomplete.getPlace();

    if (!place.place_id) {
      return;
    }

    geocoder
      .geocode({ placeId: place.place_id })
      .then(({ results }) => {
        map.setZoom(16);
        map.setCenter(results[0].geometry.location);

        input.value = '';
        var row =  input.getAttribute('data-val');
       
        document.getElementById('address-div.'+row).style.display ='block'; 
        document.getElementById('cust-address-section.'+row).innerHTML = results[0].formatted_address;
        document.getElementById('lat-section.'+row).innerHTML = results[0].geometry.location.lat();
        document.getElementById('lng-section.'+row).innerHTML = results[0].geometry.location.lng();
      
       const marker = new google.maps.Marker({
          map: map,
          draggable: true,
          position: results[0].geometry.location,
          id:row

        });

        google.maps.event.addListener(marker, 'mouseover', function() {

          geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
         
          if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
               }
          }
          });
        });

        google.maps.event.addListener(marker, 'dragend', function() {

          geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
         
          if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                
                document.getElementById('address-div.'+row).style.display ='block'; 
                document.getElementById('cust-address-section.'+row).innerHTML = results[0].formatted_address;
                document.getElementById('lat-section.'+row).innerHTML = results[0].geometry.location.lat();
                document.getElementById('lng-section.'+row).innerHTML = results[0].geometry.location.lng();
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
                // Livewire.emit('customerGetLatLngForInput', results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng(), row);
                Livewire.emit('customerGetLatLngForInput',  results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng(), row);
              }
          }
          });
        });
        Livewire.emit('customerGetLatLngForInput',  results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng(), row);
        marker.setVisible(true);
        infowindowContent.children["place-name"].textContent = place.name;
        infowindowContent.children["place-address"].textContent =
          results[0].formatted_address;
                  
        infowindow.open(map, marker);
      })
      .catch((e) => window.alert("Geocoder failed due to : " + e));

  });

  
}

// this is for customer form map 
function editBranchLocation() {
 
  const myLatlng = new google.maps.LatLng(12.972442,77.580643);
  const map = new google.maps.Map(document.getElementById("edit_map"), {
    center: myLatlng,
    zoom: 13,
  });
  const input = document.getElementById("edit-pac-input");
  const autocomplete = new google.maps.places.Autocomplete(input);

  autocomplete.bindTo("bounds", map);

  // Specify just the place data fields that you need.
  autocomplete.setFields(["place_id", "geometry", "name", "formatted_address"]);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  const infowindow = new google.maps.InfoWindow();
  const infowindowContent = document.getElementById("infowindow-content");

  infowindow.setContent(infowindowContent);

  var latitude = 23.786758526804636;
  var longitude = 90.39979934692383;
  var LatLng = new google.maps.LatLng(latitude, longitude);

  const geocoder = new google.maps.Geocoder();
  
  // const lat = document.getElementById('lat-section').attributes('data-lat');
  // const lng = document.getElementById('lng-section').attributes('data-lng');
 
   const marker = new google.maps.Marker({ 
    draggable: true,
     map: map ,
    
    });
  
  autocomplete.addListener("place_changed", () => {
    infowindow.close();

    const place = autocomplete.getPlace();

    if (!place.place_id) {
      return;
    }

    geocoder
      .geocode({ placeId: place.place_id })
      .then(({ results }) => {
        map.setZoom(16);
        map.setCenter(results[0].geometry.location);

        input.value = '';
       
        // document.getElementById('address-div').style.display ='block'; 
        document.getElementById('cust-address-section').innerHTML = results[0].formatted_address;
        document.getElementById('lat-section').innerHTML = results[0].geometry.location.lat();
        document.getElementById('lng-section').innerHTML = results[0].geometry.location.lng();
      
       const marker = new google.maps.Marker({
          map: map,
          draggable: true,
          position: results[0].geometry.location
        });

        google.maps.event.addListener(marker, 'mouseover', function() {

          geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
         
          if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
               }
          }
          });
        });

        google.maps.event.addListener(marker, 'dragend', function() {

          geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
         
          if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                
                document.getElementById('cust-address-section').innerHTML = results[0].formatted_address;
                document.getElementById('lat-section').innerHTML = results[0].geometry.location.lat();
                document.getElementById('lng-section').innerHTML = results[0].geometry.location.lng();
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
                // // Livewire.emit('customerGetLatLngForInput', results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng(), row);
                Livewire.emit('customerLatLngChange',  results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng());
              }
          }
          });
        });
        Livewire.emit('customerLatLngChange',  results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng());
        marker.setVisible(true);
        infowindowContent.children["edit-place-name"].textContent = place.name;
        infowindowContent.children["edit-place-address"].textContent =
          results[0].formatted_address;
                  
        infowindow.open(map, marker);
      })
      .catch((e) => window.alert("Geocoder failed due to asada: " + e));

  });

  
}


function addNewBranch() {
 
  const myLatlng = new google.maps.LatLng(12.972442,77.580643);
  const map = new google.maps.Map(document.getElementById("edit_map"), {
    center: myLatlng,
    zoom: 13,
  });
  const input = document.getElementById("edit-pac-input");
  const autocomplete = new google.maps.places.Autocomplete(input);

  autocomplete.bindTo("bounds", map);

  // Specify just the place data fields that you need.
  autocomplete.setFields(["place_id", "geometry", "name", "formatted_address"]);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  const infowindow = new google.maps.InfoWindow();
  const infowindowContent = document.getElementById("infowindow-content");

  infowindow.setContent(infowindowContent);

  var latitude = 23.786758526804636;
  var longitude = 90.39979934692383;
  var LatLng = new google.maps.LatLng(latitude, longitude);

  const geocoder = new google.maps.Geocoder();
  
  // const lat = document.getElementById('lat-section').attributes('data-lat');
  // const lng = document.getElementById('lng-section').attributes('data-lng');
 
   const marker = new google.maps.Marker({ 
    draggable: true,
     map: map ,
    
    });
  
  autocomplete.addListener("place_changed", () => {
    infowindow.close();

    const place = autocomplete.getPlace();

    if (!place.place_id) {
      return;
    }

    geocoder
      .geocode({ placeId: place.place_id })
      .then(({ results }) => {
        map.setZoom(16);
        map.setCenter(results[0].geometry.location);

        input.value = '';
       
        // document.getElementById('address-div').style.display ='block'; 
        document.getElementById('cust-address-section').innerHTML = results[0].formatted_address;
        document.getElementById('lat-section').innerHTML = results[0].geometry.location.lat();
        document.getElementById('lng-section').innerHTML = results[0].geometry.location.lng();
      
       const marker = new google.maps.Marker({
          map: map,
          draggable: true,
          position: results[0].geometry.location
        });

        google.maps.event.addListener(marker, 'mouseover', function() {

          geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
         
          if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
               }
          }
          });
        });

        google.maps.event.addListener(marker, 'dragend', function() {

          geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
         
          if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                
                document.getElementById('cust-address-section').innerHTML = results[0].formatted_address;
                document.getElementById('lat-section').innerHTML = results[0].geometry.location.lat();
                document.getElementById('lng-section').innerHTML = results[0].geometry.location.lng();
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
                // // Livewire.emit('customerGetLatLngForInput', results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng(), row);
                Livewire.emit('customerLatLngChange',  results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng());
              }
          }
          });
        });
        Livewire.emit('customerLatLngChange',  results[0].formatted_address,results[0].geometry.location.lat(),results[0].geometry.location.lng());
        marker.setVisible(true);
        infowindowContent.children["edit-place-name"].textContent = place.name;
        infowindowContent.children["edit-place-address"].textContent =
          results[0].formatted_address;
                  
        infowindow.open(map, marker);
      })
      .catch((e) => window.alert("Geocoder failed due to asada: " + e));

  });

  
}

function dashboardMap(locations){
  
  
  // var locations = [
  //                     ['Bondi Beach', 12.972442,77.580643, 4],
  //                     ['Coogee Beach', 12.872442,77.680643, 5],
  //                     ['Cronulla Beach', 12.772442,77.780643, 3],
  //                     ['Manly Beach', 12.672442,77.880643, 2],
  //                     ['Maroubra Beach', 12.572442,77.980643, 1]
  //      ];

  // var locations = [
  //                     ["Janagiraman",19.009883,77.9988899,0],
  //                     ["Muhesh",19.997883,77.9987899,1],
  //                     ["Muhesh",19.165883,77.1985899,2],
  //                     ["Janagiraman",19.129883,77.9988899,3]
  //                 ];

  // var locations = locations;
  //var locations = {{$this->latLong}} 
  

  console.log(locations);
  console.log(locations.length);
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 10,
    center: new google.maps.LatLng(19.009883,77.9988899),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  var infowindow = new google.maps.InfoWindow();

  var marker, i;

  for (i = 0; i < locations.length; i++) {  
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[i][1], locations[i][2]),
      map: map
    });

    google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
      return function() {
        infowindow.setContent(locations[i][0]);
        infowindow.open(map, marker);
      }
    })(marker, i));
  }

  
}
 


  
  