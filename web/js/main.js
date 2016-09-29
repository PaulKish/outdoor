// Document ready
$(document).ready(function() {
	$("#menu-toggle").click(function(e) {
	    e.preventDefault();
	    $("#wrapper").toggleClass("active");
	    // change toggle class
	    $("#fa-toggle").toggleClass("fa-angle-double-right fa-angle-double-left");
	});
});