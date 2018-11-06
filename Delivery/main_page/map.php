<!DOCTYPE html>
<html>
  <head>
    <title>Place Autocomplete</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <script src="/Delivery/jquery.js"></script>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 50%;
        width:100%;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
      }

      #infowindow-content .title {
        font-weight: bold;
      }

      #infowindow-content {
        display: none;
      }

      #map #infowindow-content {
        display: inline;
      }

      #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
      }

      .pac-controls {
        display: inline-block;
        padding: 5px 11px;
      }

      .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 200px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
      }
      .pac-card {
        float: left;
      }
      .ad-right {
        float: right;
        margin-left: 10px;
      }

      .entire-thing {
        width: 650px;
      }
    </style>
  </head>
  <body>
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="pac-card" id="pac-card">
          <div id="pac-container">
            <input style="align:left" id="pac-input" type="text" placeholder="Enter a location">
          </div>
        </div>
        <div id="map"></div>
        <div id="infowindow-content">
          <img src="" width="16" height="16" id="place-icon">
          <span id="place-name"  class="title"></span><br>
          <span id="place-address"></span>
        </div>
      </div>
    </div>
  </div>
  <script type="text/jscript">

      function enableState(){
        document.getElementById("mySelect").disabled = false;
      }

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 38.246229, lng: 21.735175},
          zoom: 15
        });
        var card = document.getElementById('pac-card');
        var input = document.getElementById('pac-input');
        var geocoder = new google.maps.Geocoder;
        //map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
        var autocomplete = new google.maps.places.Autocomplete(input);

        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        autocomplete.bindTo('bounds', map);


        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);

        document.getElementById('confirmO').addEventListener('click', function() {
                  document.getElementById("pac-card").style.display='none';
                  infowindow.close();
                  geocodeAddress(geocoder, map);
                });


        var marker = new google.maps.Marker({
          map: map,
          draggable:true
        });


        autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          enableState();
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }

          // If the place has a geometry, then present it on a map.
         if (place.geometry.viewport) {
           map.fitBounds(place.geometry.viewport);
         } else {
           map.setCenter(place.geometry.location);
           map.setZoom(17);  // Why 17? Because it looks good.
         }
         marker.setPosition(place.geometry.location);
         marker.setVisible(true);
         geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
         if (status == google.maps.GeocoderStatus.OK) {
         if (results[0]) {
         var lat = results[0].geometry.location.lat();
         var long = results[0].geometry.location.lng();
         infowindow.setContent(results[0].formatted_address);
         input.value = results[0].formatted_address;
         infowindow.open(map, marker);
         postlatlng(lat,long);
       }
       }
       });

         var address = 'place.geometry.location';
         if (place.address_components) {
           address = place.formatted_address;
         }
          infowindowContent.children['place-icon'].src = place.icon;
          infowindowContent.children['place-name'].textContent = place.name;
          infowindowContent.children['place-address'].textContent = address;
          document.getElementById('addr').innerHTML = address;
          infowindow.open(map, marker);
        });



        marker.addListener('dragend', function(e) { //? ???st?? µp??e? ?a t?aß??e? t?? marker ?a? ?a ?a?e? finetune st?? t?p??es?a t??
          marker.setPosition(e.latLng);
          geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {
          var lat = results[0].geometry.location.lat();
          var long = results[0].geometry.location.lng();
          infowindow.setContent(results[0].formatted_address);
          input.value = results[0].formatted_address;
          infowindow.open(map, marker);
          postlatlng(lat,long);
          enableState();
          }
          }
          });
          });


        map.addListener('click', function(e) { //? ???st?? µp??e? ?a ep????e? µ????
          marker.setPosition(e.latLng);
          geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {
          var lat = results[0].geometry.location.lat();
          var long = results[0].geometry.location.lng();
          infowindow.setContent(results[0].formatted_address);
          input.value = results[0].formatted_address;
          infowindow.open(map, marker);
          postlatlng(lat,long);
          enableState();


          }
          }
          });
          });
          function postlatlng(lat,long){
            $.post('sendlatlng.php',{latitude:lat,longitude:long},
            function(data){
              $('#result').html(data);
            });
          }
          function geocodeAddress(geocoder, resultsMap) {
            geocoder.geocode({'address': document.getElementById("address").innerHTML}, function(results, status) {
              if (status === 'OK') {
                resultsMap.setCenter(results[0].geometry.location);
                marker.setPosition(results[0].geometry.location);
                infowindow.setContent(results[0].formatted_address);
                input.value = results[0].formatted_address;
                infowindow.open(map, marker);
              } else {
                alert('Geocode was not successful for the following reason: ' + status);
              }
            });
          }
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQGV104kVX8BzSI-XRguLIIkZwMBxdrlo&libraries=places&callback=initMap" async defer></script>
  </body>
</html>
