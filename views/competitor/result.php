<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\common\ExportMenu;

/* @var $this yii\web\View */
$this->title = 'Competitor Analysis | Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competitor-result container-fluid">
	<div class="row">
		<div class="col-md-12 white-background">
			<?php 
				$gridColumns = [
				    ['class' => 'yii\grid\SerialColumn'],
				    'brand.company.company_name',
			        'brand.brand_name',
			        'brand.subIndustry.sub_industry_name',
			        'bbCompany.company_name',
			        'bb_size',
			        'date_time:datetime',
			        [
				        'format' => 'raw',
				        'label' => 'Photo',
				        'headerOptions' => ['class' => 'text-center'],
				        'contentOptions' => ['class' => 'text-center'],
			            'value' => function ($data) {
			                return Url::to(['site/photo','photo'=>$data->photo],true);
			            }
			        ],
			        [
			            'format' => 'raw',
				        'label' => 'Location',
				        'headerOptions' => ['class' => 'text-center'],
				        'contentOptions' => ['class' => 'text-center'],
			            'value' => function ($data) {
			                return Url::to(['/site/map','lat'=>$data->lattitude,'long'=>$data->longitude],true);
			            }
			        ],
				];

				// Renders a export dropdown menu
				echo ExportMenu::widget([
				    'dataProvider' => $dataProvider,
				    'columns' => $gridColumns,
				    'target'=> ExportMenu::TARGET_SELF,
				    'showConfirmAlert'=>false,
				    'showColumnSelector'=>false,
				    'filename'=>'competitor-export',
				    'dropdownOptions'=>[
				    	'icon'=>'<i class="glyphicon glyphicon-export"></i>',
				    	'label'=>'Export'
				    ],
				    'exportConfig'=>[
				    	ExportMenu::FORMAT_TEXT => false,
				    	ExportMenu::FORMAT_HTML => false,
				    	ExportMenu::FORMAT_EXCEL => false,
				    	ExportMenu::FORMAT_CSV => false
				    ]
				]);

				echo '<hr>'; 	

				echo GridView::widget([
				    'dataProvider' => $dataProvider,
				    'layout'=>"{items}\n{summary}\n{pager}",
				    'columns' => [
				        ['class' => 'yii\grid\SerialColumn'],
				        'brand.company.company_name',
				        'brand.brand_name',
				        'brand.subIndustry.sub_industry_name',
				        'bbCompany.company_name',
				        'bb_size',
				        'date_time:datetime',
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
<?php 
	$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCPA3IbbIblDCKLZ4obKt6wP4eaO3Qguzs');
?>