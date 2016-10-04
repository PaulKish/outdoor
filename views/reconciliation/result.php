<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Reconciliation Log | Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reconciliation-result container-fluid">
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
				        'date_time:datetime',
				        'entry_time:datetime',
				        'rate',
				        [
					        'format' => 'raw',
					        'label' => 'Photo',
					        'headerOptions' => ['class' => 'text-center'],
					        'contentOptions' => ['class' => 'text-center'],
				            'value' => function ($data) {
				                return Html::a('<i class="fa fa-lg fa-picture-o"></i>','#',
				                	['class'=>'photo-modal','data'=>['photo'=>$data->photo]]
				                );
				            }
				        ],
				        [
				            'format' => 'raw',
					        'label' => 'Location',
					        'headerOptions' => ['class' => 'text-center'],
					        'contentOptions' => ['class' => 'text-center'],
				            'value' => function ($data) {
				                return Html::a('<i class="fa fa-lg fa-map-marker"></i>','#',
				                	[
				                		'class'=>'location-modal',
				                		'data'=>[
				                			'latitude'=>$data->lattitude,
				                			'longitude'=>$data->longitude
				                		]
				                	]
				                );
				            }
				        ],
				    ],
				]);
			?>
		</div>
	</div>
</div>