// Document ready
$(document).ready(function() {

	// menu visibility
	function menu_toggle(){
		$("#wrapper").toggleClass("active");
	    // change toggle class
	    $("#fa-toggle").toggleClass("fa-angle-double-right fa-angle-double-left");
	}

	// toggle the side bar
	$("#menu-toggle").click(function(e) {
	    e.preventDefault();
	    menu_toggle();
	});

	// if screen is small hide menu else show untoggled
	var screen_width = $(window).width();
	if(screen_width <= 768){
		menu_toggle();
	}


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
		var img = $(this).data("img");
		$('#siteModal').find('.modal-content').html('<div class="map-div" id="map"></div>');
		$('#siteModal').modal('show',{cache:false});
		$('#siteModal').on('shown.bs.modal', function(e) {
			initMap(latitude,longitude,img); // init google map
		});
	});
});

// google map init
function initMap(lat,long,img) {
    var myLatLng = {lat:lat,lng:long};

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      center: myLatLng
    });

    var infowindow = new google.maps.InfoWindow({
	    content: "<img width='100' src='http://reelapp.reelforge.com/rf_outdoor/uploads/"+img+"'></img>"
	});

    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      title: 'BillBoard Location'
    });

    marker.addListener('click', function() {
	    infowindow.open(map, marker);
	});

}

// toggle checkbokes, used in brand selection
function toggle_checkboxes(source,name) {
  	checkboxes = document.getElementsByName(name);
  	for(var i=0, n=checkboxes.length;i<n;i++) {
    	checkboxes[i].checked = source;
  	}
}