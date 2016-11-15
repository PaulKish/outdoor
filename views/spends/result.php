<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\common\ExportMenu;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
$this->title = 'Total Spends | Report by Company';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competitor-result container-fluid">
	<div class="row">
		<div class="col-md-12 white-background">
			<?php 
				$gridColumns = [
				    ['class' => 'yii\grid\SerialColumn'],
				    'brand.company.company_name',
			        'total'
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

				$data = ArrayHelper::map($dataProvider->getModels(), 'brand.company.company_name','total');
				$chart = [];
				foreach($data as $key => $value){
					$chart[] = ['name'=>$key,'y'=>(int)$value];
				}

				echo \dosamigos\highcharts\HighCharts::widget([
				    'clientOptions' => [
				        'chart' => [
				                'type' => 'pie'
				        ],
				        'title' => [
			             	'text' => 'Total Spends by Company'
			            ],
			            'tooltip'=> [
				            'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
				        ],
				        'plotOptions'=> [
				            'pie'=> [
				                'allowPointSelect'=> true,
				                'cursor' => 'pointer',
				                'dataLabels' => [
				                    'enabled'=> true,
				                    'format'=> '<b>{point.name}</b>: {point.percentage:.1f} %',
				                    'style' => [
				                        'color' => "(Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'"
				                    ]
				                ]
				            ]
				        ],
			            'series' => [[
				            'name' => 'Companies',
				            'colorByPoint' => true,
				            'data' => $chart
				        ]] 
				    ]
				]);

				echo '<hr>'; 	

				echo GridView::widget([
				    'dataProvider' => $dataProvider,
				    'layout'=>"{items}\n{summary}\n{pager}",
				    'columns' => [
				        ['class' => 'yii\grid\SerialColumn'],
				        'brand.company.company_name',
				        [
						    'attribute' => 'total',
						    'contentOptions' => ['class' => 'text-right'],
						    'headerOptions' => ['class' => 'text-right'],
						    'format' => ['decimal']
						],
				    ],
				]);

			?>
		</div>
	</div>
</div>