<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DateRangePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ReconciliationFilterForm */
/* @var $form ActiveForm */

$this->title = 'NCC Log';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reconciliation-index container-fluid">
	<div class="row">
		<div class="col-md-12 white-background">
		    <?php $form = ActiveForm::begin(['layout'=>'inline','action'=>'result']); ?>

		    	<div class="row">
		        	<div class="col-sm-3">
		        		<h5>Region</h5>
				        <hr>
				        <?= $form->field($model, 'region')->dropDownList(
				        		ArrayHelper::map($regions, 'code', 'name'),
				        		['prompt'=>'--All regions--']
				        	) 
				        ?>
		        	</div>
		        	<div class="col-sm-3">
		        		<h5>Billboard Condition</h5>
				        <hr>
				        <?= $form->field($model, 'condition')->dropDownList(
				        		ArrayHelper::map($conditions, 'id', 'condition'), 
				        		['prompt'=>'--All conditions--']
				        	) 
				        ?>
		        	</div>
		        	<div class="col-sm-6">
		        		<h5>Billboard Type</h5>
				        <hr>
				        <?= $form->field($model, 'type')->dropDownList(
				        		ArrayHelper::map($types, 'id', 'type'),
				        		['prompt'=>'--All types--']
				        	) 
				        ?>
		        	</div>
		        </div>

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
</div><!-- reconciliation-index -->