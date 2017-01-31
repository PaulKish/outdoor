<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DateRangePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CompetitorFilterForm */
/* @var $form ActiveForm */

$this->title = 'Compliance Calculator';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competitor-index container-fluid">
	<div class="row">
		<div class="col-md-12 white-background">
		    <?php $form = ActiveForm::begin(['layout'=>'inline','action'=>'result']); ?>

		    	<h5>Billboard Company</h5>
		    	<hr>
		    	<?= $form->field($model, 'billboard_company')->dropDownList(
		        		ArrayHelper::map($bbcompanies, 'co_id', 'company_name'),
		        		['prompt'=>'--All billboard companies--']
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

				<h5>Billboards Booked</h5>
				<hr>
				<?= $form->field($model, 'count')->textInput(['placeholder'=>'Count']) ?>
		    
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