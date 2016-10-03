<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DateRangePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\FlightFilterForm */
/* @var $form ActiveForm */

$this->title = 'Proof of Flight';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flight-index container-fluid">
	<div class="row">
		<div class="col-md-12 white-background">
		    <?php $form = ActiveForm::begin(['layout'=>'inline']); ?>

		    	<h5>Brand <small><a href="#" onClick="toggle_checkboxes(1,'FlightFilterForm[brand][]')">Check all brands</a> | <a href="#" onClick="toggle_checkboxes(0,'FlightFilterForm[brand][]')">Uncheck all brands</a></small></h5>
		    	<hr>
		    	<div class="scrollbox">
				  	<?= $form->field($model, 'brand')->checkboxList(
			        		ArrayHelper::map($brands, 'brand_id', 'brand_name')
			        	) 
			        ?>
				</div>
		        
		        <hr>

		        <h5>Date</h5>
		        <hr>
		        <?= $form->field($model, 'start_date')->widget(
					    DateRangePicker::className(), [
					    	'attributeTo' => 'end_date', 
    						'form' => $form,
					        'clientOptions' => [
					            'autoclose' => true,
					            'format' => 'yyyy-mm-dd'
					        ]
					]) 
				?>
		    
		    	<hr>
		    	
		        <div class="form-group">
		            <?= Html::submitButton('Generate', ['class' => 'btn btn-success']) ?>
		        </div>
		        <br>
		        <br>
		        
		    <?php ActiveForm::end(); ?>
	    </div>
	</div>
</div><!-- flight-index -->