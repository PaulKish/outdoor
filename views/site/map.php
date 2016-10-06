<?php
	use yii\web\View;

	$this->title = 'Map';
?>
<div class="map-div-big" id="map"></div>
<?php 
	$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCPA3IbbIblDCKLZ4obKt6wP4eaO3Qguzs');
	$this->registerJs("initMap($lat,$long);", View::POS_END, 'google-map');
?>