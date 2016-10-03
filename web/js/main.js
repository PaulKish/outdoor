// Document ready
$(document).ready(function() {
	// ?
	$("#menu-toggle").click(function(e) {
	    e.preventDefault();
	    $("#wrapper").toggleClass("active");
	    // change toggle class
	    $("#fa-toggle").toggleClass("fa-angle-double-right fa-angle-double-left");
	});

	// shows a modal containing a picture
	$(".photo-modal").on('click',function(e){
		var photo = $(this).data("photo");
		$('#siteModal').find('.modal-content').html('<img src="'+photo+'">');
		$('#siteModal').modal('show');
	});

	// shows modal containing a map
	$(".location-modal").on('click',function(e){
		var latitude = $(this).data("latitude");
		var longitude = $(this).data("longitude");
		$('#siteModal').find('.modal-content').html('<div class="map-div" id="map"></div>');
		$('#siteModal').modal('show');
		initMap(latitude,longitude); // init google map
	});

	// google map init
	function initMap(lat,long) {
	    var myLatLng = {lat:lat,lng:long};

	    var map = new google.maps.Map(document.getElementById('map'), {
	      zoom: 11,
	      center: myLatLng
	    });

	    var marker = new google.maps.Marker({
	      position: myLatLng,
	      map: map,
	      title: 'Map Marker'
	    });
  	}
});

// toggle checkbokes, used in brand selection
function toggle_checkboxes(source,name) {
  	checkboxes = document.getElementsByName(name);
  	for(var i=0, n=checkboxes.length;i<n;i++) {
    	checkboxes[i].checked = source;
  	}
}