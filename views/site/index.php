<?php
/* @var $this yii\web\View */
use Ivory\GoogleMap\Helper\Builder\MapHelperBuilder;

$this->title = 'Dashboard';

?>
<div class="flight-index container-fluid">
	<div class="row">
		<div class="col-md-12 white-background">
			<h4>Billboard Locations</h4>
			<?= $map->display(); ?>
		</div>
	</div>
</div>