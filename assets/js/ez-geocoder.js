!( function($){

	$( document ).ready(function(){
		
	 	var coordinatesInput = $('#ez-gm-coordinates input');

	 	//Check to see if input fields contain coordinates

		$.each(coordinatesInput, function(){
		    if(!$.trim(this.value)){

		      $(this).addClass('incomplete');
		      
		    } else {

		      $(this).addClass('complete');
		    
		    }
		}); 

		//Run geocode function when address is entered

		$(document).on("keyup focusout", '#ez-gm-address input', throttle(function(event) {
		   geocodeAddress(this);
		}, 1000));

		//Throttle function

		function throttle(func, interval) {
		    var lastCall = 0;
		    return function() {
		        var now = Date.now();
		        if (lastCall + interval < now) {
		            lastCall = now;
		            return func.apply(this, arguments);
		        }
		    };
		}

		//Geocode function

		function geocodeAddress(ele) {

			var addressInput = $(ele).val();

			var parentRow = $(ele).closest( '.cmb-row' );

			var nextParentRow = parentRow.find('#ez-gm-coordinates input').attr( 'id' );

			var currentCoordinatesInput = parentRow.find('#ez-gm-coordinates input');

			var geocoder = new google.maps.Geocoder();
			
			var coordinateInputBox = '#ez-gm-coordinates input#'+ nextParentRow;

			geocoder.geocode( { 'address': addressInput }, function( results, status ) {

				if (status === 'OK') {

					currentCoordinatesInput.addClass( "complete" ).removeClass( "incomplete" );

					var sval = results[0].geometry.location.toString();

					var value = $( '#ez-gm-coordinates input#'+ nextParentRow ).val( sval );

				} else {

					currentCoordinatesInput.addClass('incomplete').removeClass( "complete" );

					console.log( 'Geocode was not successful for the following reason: ' + status );

				}

			});
		}

	});
})(window.jQuery);