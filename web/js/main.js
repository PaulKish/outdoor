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
		$('#siteModal').find('.modal-content').html('<div class="bb-photo"><img class="img-responsive" src="http://reelapp.reelforge.com/rf_outdoor/uploads/'+photo+'"></div>');
		$('#siteModal').modal('show',{cache:false});
	});

	// shows modal containing a map
	$(".location-modal").on('click',function(e){
		var latitude = $(this).data("latitude");
		var longitude = $(this).data("longitude");
		$('#siteModal').find('.modal-content').html('<div class="map-div" id="map"></div>');
		$('#siteModal').modal('show',{cache:false});
		$('#siteModal').on('shown.bs.modal', function(e) {
			initMap(latitude,longitude); // init google map
		});
	});

	// google map init
	function initMap(lat,long) {
	    var myLatLng = {lat:lat,lng:long};

	    var map = new google.maps.Map(document.getElementById('map'), {
	      zoom: 10,
	      center: myLatLng
	    });

	    var marker = new google.maps.Marker({
	      position: myLatLng,
	      map: map,
	      title: 'BillBoard Location'
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