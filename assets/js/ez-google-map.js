
  var ez_gm_map = document.getElementById("ez-google-map");

  var my_json_str = php_vars.ez_gm_markers.replace(/&quot;/g, '"');

  //Convert to json object

  var markers = JSON.parse(my_json_str);

  map = new google.maps.Map( ez_gm_map );

  var infoWindow = new google.maps.InfoWindow();

  var latlngbounds = new google.maps.LatLngBounds();

  var latLngObj = []

  for (var i = 0; i < markers.length; i++) {

    var data = markers[i];

    (function codeAddress() {

        var address = data['ez-gm-coordinates'];

        var description = data['ez-gm-content'];
        
        var pin = data['ez-gm-map-pin'];

        if ( pin == false ) {

          pin = "http://maps.google.com/mapfiles/marker.png";
        
        };

        //Remove brackets

        var address = data['ez-gm-coordinates'].replace(/[\])}[{(]/g, '');

        //Split latlng value

        var addressLatlng = address.split(',');

        //Convert latlng values into numbers

        var lat = parseFloat(addressLatlng[0]);

        var lng = parseFloat(addressLatlng[1]);

        //Assign latlng values to variables

        var myLatLng = {lat: lat, lng: lng};

        var latLng = new google.maps.LatLng(myLatLng);

        latlngbounds.extend(latLng);

        var marker = new google.maps.Marker({
         
          map: map,
          position: latLng,
          icon: pin

        });
        
          //Attach click event to the marker.
         
          (function (marker, data) {
            
              google.maps.event.addListener(marker, "click", function (e) {

                  //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
                  infoWindow.setContent("<div style = 'width:200px;min-height:40px'>" + description + "</div>");
                  infoWindow.open(map, marker);
              
              });

          })(marker, data);

     })();
     //codeAddress

  }//end for loop

  map.fitBounds(latlngbounds);
