<?php
/* @var $this yii\web\View */
use Ivory\GoogleMap\Helper\Builder\MapHelperBuilder;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Dashboard';

?>
<div class="flight-index container-fluid">
	<div class="row">
		<div class="col-md-12 white-background">
			<?php $form = ActiveForm::begin(['layout'=>'inline']); ?>
			<div class="pull-left">
				<h4>Billboard Locations</h4>
			</div>
			<div class="pull-right">
				<?= $form->field($model, 'region')->dropDownList(
		        		ArrayHelper::map($regions, 'code', 'name'),
		        		['prompt'=>'--Please Select--']
		        	) 
		        ?>
				<?= Html::submitButton('Filter', ['class' => 'btn btn-success']) ?>
			</div>
			
			<?= $map->display(); ?>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>