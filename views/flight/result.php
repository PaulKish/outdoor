<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = 'Proof of Flight | Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flight-index container-fluid">
	<div class="row">
		<div class="col-md-12 white-background">
			<?php 
				$dataProvider = new ActiveDataProvider([
				    'query' => $logs,
				    'pagination' => [
				        'pageSize' => 20,
				    ],
				]);
				echo GridView::widget([
				    'dataProvider' => $dataProvider,
				    'columns' => [
				        ['class' => 'yii\grid\SerialColumn'],
				        'bbCompany.company_name',
				        'date_time',
				        'entry_time',
				        'rate',
				        [
				            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
				            'value' => function ($data) {
				                return $data->photo;
				            }
				        ],
				        [
				            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
				            'value' => function ($data) {
				                return $data->lattitude.$data->longitude;
				            }
				        ],
				    ],
				]);
			?>
		</div>
	</div>
</div>
