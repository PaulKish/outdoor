<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DateRangePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CompetitorFilterForm */
/* @var $form ActiveForm */

$this->title = 'Total Spends | Company';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competitor-index container-fluid">
	<div class="row">
		<div class="col-md-12 white-background">
		    <?php $form = ActiveForm::begin(['layout'=>'inline','action'=>'result']); ?>

		    	<h5>Industry</h5>
		    	<hr>
		    	<?= $form->field($model, 'industry')->dropDownList(
		        		ArrayHelper::map($industry, 'industry_id', 'industry.industry_name'),
		        		['prompt'=>'--Please Select--']
		        	) 
		        ?>
		        
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