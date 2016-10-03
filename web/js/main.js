// Document ready
$(document).ready(function() {
	$("#menu-toggle").click(function(e) {
	    e.preventDefault();
	    $("#wrapper").toggleClass("active");
	    // change toggle class
	    $("#fa-toggle").toggleClass("fa-angle-double-right fa-angle-double-left");
	});
});

// toggle checkbokes, used in brand selection
function toggle_checkboxes(source,name) {
  	checkboxes = document.getElementsByName(name);
  	for(var i=0, n=checkboxes.length;i<n;i++) {
    	checkboxes[i].checked = source;
  	}
}