
	$.ajax({
        type: "GET",
        url: "home",
        dataType: "json",       
        success: function (data) { 
        	console.log(data);		
			initMap(data);    		
        },
        error: function (xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        }
    });


	var map;
	var markers = [];
    var infowindow;    
    
	var bounds = new google.maps.LatLngBounds();

    function initMap(data) {    	

    	var devices = data.mapObj;
    	console.log(devices);
    	var Latlng;
    	if(devices.length){

    		Latlng = {lat: devices[0].coords.lat, lng: devices[0].coords.lng};
		}

        var mapOptions = {
            zoom: 2,
            center: Latlng
        };

        map = new google.maps.Map(document.getElementById('map'), mapOptions);      

		function drop() {
			clearMarkers();

		    for (var i = 0; i < devices.length; i++) {
		        addMarkerWithTimeout(devices[i], i * 200);               
		    }
		}

       drop();
    }

    function addMarkerWithTimeout(position, timeout) {
	    window.setTimeout(function() {
	    	createMarker(position.coords, position.title, position.address, position.imei, position.id );              

	    }, timeout);
	}

	function clearMarkers() {
	    for (var i = 0; i < markers.length; i++) {
	      markers[i].setMap(null);
	      //markers[i].setContent(null);
	      //google.maps.event.removeListener(markers[i]);
	    }
	    markers = [];
	}

	function createMarker(latlng, title, address, imei, id) {

		var newContent = '<table class="infowindow-box">'+
          	'<tr>'+
                '<th>Name</th>'+
                '<th>Info</th>'+
      		'</tr>'+
          	'<tr>'+
                '<td>address: </td>'+
                '<td>' + ( address == 'result_not_found' ? 'Address not found' : address) + '</td>'+
          	'</tr>'+
          	'<tr>'+
	        	'<td>Imei: </td>'+
	            '<td>'+ imei +'</td>'+
          	'</tr>'+
        '</table>';
		var marker = new google.maps.Marker({
			id: id,
			title: title,
		    map: map,
		    position: latlng,
		    animation: google.maps.Animation.DROP
		});

		var infowindow = new google.maps.InfoWindow({
            content: newContent
        });
		google.maps.event.addListener(marker, 'click', function() {				
			infowindow.open(map, marker);
		});

		bounds.extend(marker.getPosition());
		map.fitBounds(bounds);

	  	markers.push(marker);
	  	
	}

	function deleteMarker(id) {
		 for (var i = 0; i < markers.length; i++) {
		 	//console.log(markers);
		 	if(markers[i].id === id ){
	      		markers[i].setMap(null);	      
		 	}
	    }
	}  


	$(document).on("change", ".geo-mark", function() {
		var csrf_token = $('meta[name="csrf-token"]').attr('content');		
   		var geoId = $(this).data("geoid"); 
   		var state = $(this).is(':checked');
   		var stateToBool = state ? 1 : 0;
		
	   	$.ajax({
	        type: "POST",
	        url: "update",	        
	        data: { "_token":csrf_token, "geoId":geoId, "state":stateToBool },
	        dataType: 'json',	        
	       	beforeSend: function() {
        		$('.devices-list').prepend('<div class="spinner"></div>');        		
      		},	        
	        success: function (data) {
	        	console.log(data);
  				$('.devices-list .spinner').remove();
  				switch(state) {
				  case true:				    	
	  					createMarker({lat: data.newDevice.latitude, lng: data.newDevice.longitude}, 
	  					data.newDevice.name, 
	  					data.address.formatted_address, 
	  					data.newDevice.imei, 
	  					data.newDevice.id);		  				       	
				    break;
				  case false:
				    	deleteMarker(data.newDevice.id);
				    break;
				  default:
				    	//deleteMarker(data.newDevice.id);
				}		
	        },
	        error: function (xhr, textStatus, errorThrown) {
	            alert("Error: " + errorThrown);
	        }
	    });
		
	});  

	$('#add-device-form').on('submit', function(e){
	   	e.preventDefault();
	   	//var form = $('#add-device-form');
	   	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	   	var formData = $(this).serialize();
	   	var _token = $("input[name='_token']").val();
	   	var deviceName = $('#device-name').val();	   	
	   	var deviceImei = $('#device-imei').val();	   	
	   	var deviceLatitude = $('#latitude').val();	   	
	   	var deviceLongitude = $('#longitude').val();	   	
	   	//var deviceState = $('#device-set').val();	   	  	
	   	$.ajaxSetup({
			headers: {
		  		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
	  	});
	   	$.ajax({
	  		type: 'POST',
	  		url: "update-add-device",
	  		data: { "deviceName":deviceName, 
	  				"deviceImei":deviceImei, 
	  				"deviceLatitude":deviceLatitude, 
	  				"deviceLongitude": deviceLongitude },	  				
	  		//dataType: "json",
	  		success: function(data){	  			
	      		//console.log(data);
	      		refreshPages();
	      		if(data.errors != 'undefined'){	      			
		      		$.each(data.errors, function(key, value){
		      			if(!$('input[name='+ key +']').parent().find('.alert-danger').length){
	      					$('input[name='+ key +']').parent().append('<p class="alert-danger">'+value+'</p>');	      			
		      			}
		      		});	      			
	      		}  
	      		if(data.msg != 'undefined'){
	      			$('#myModal').find('.modal-body').prepend('<p class="alert-success">'+data.msg+'</p>');
	      		}    			
			}	
		});
   	});

   	function refreshPages(){
   		$.ajaxSetup({
			headers: {
		  		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
	  	});
   		$.ajax({
   			//type: 'GET',
			url: "get-pages",
			dataType: 'json',
			beforeSend: function() {
        		$('.devices-list').prepend('<div class="spinner"></div>');        		
      		},	  
			success: function(data){
				$('.devices-list .spinner').remove();
				$('.devices-holder').html(data.html);				
			}
   		});
   	}

   	$(document).on('click', '.devices-holder ul.pagination li a', function(e){
   		e.returnValue = true;
   		e.preventDefault();
   		
   		var page = $(this).attr('href').split('page=')[1];   		
   		getPages(page);
   		
   	});

   	function getPages(page){
   		$.ajaxSetup({
			headers: {
		  		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
	  	});
   		$.ajax({
   			//type: 'GET',
			url: "get-pages?page="+page,
			dataType: 'json',
			beforeSend: function() {
        		$('.devices-list').prepend('<div class="spinner"></div>');
        		
      		},	    
			success: function(data){
				$('.devices-list .spinner').remove();
				$('.devices-holder').html(data.html);				
			}
   		});
   	}

$( document ).ready(function() { 
   	
});