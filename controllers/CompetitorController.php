<?php

namespace app\controllers;

use app\models\CompetitorFilterForm;
use app\models\OutdoorLogs;
use app\models\forge\IndustryReport;
use app\models\forge\Brand;
use app\models\forge\SubIndustry;
use app\models\forge\Competitor;
use app\models\BillboardType;
use app\models\BillboardSites;
use app\models\Counties;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\LatLngBounds;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;

class CompetitorController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }
    

    public function actionIndex()
    {
    	$model = new CompetitorFilterForm();

        // profile, get cuser type 
        $profile = \Yii::$app->user->identity->profile;
        $types = BillboardType::find()->all();
        $regions = Counties::find()->all();
        $competition = Competitor::find()->where(['company_id'=>$profile->type_id])->all();

        return $this->render('index', [
            'model' => $model,
            'types' => $types,
            'regions' => $regions,
            'competition' => $competition
        ]);
    }

    /**
     *  Show grid of results
     */
    public function actionResult(){
        $model = new CompetitorFilterForm();
        $session = \Yii::$app->session;
        $profile = \Yii::$app->user->identity->profile; // get company data

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                // store model params in session
                $session['start_date'] = $model->start_date;
                $session['end_date'] = $model->end_date;
                $session['region'] = $model->region;
                $session['type'] = $model->type;
                
                // if all competition is selected then pull all competition
                if($model->competition == ''){
                    $competition = Competitor::find()->where(['company_id'=>$profile->type_id])->all();
                    $competitor_list = ArrayHelper::getColumn($competition,'competitor_id');
                    $competitor_list[] = $profile->type_id; // include own company in list
                    $session['competition'] = $competitor_list; 
                }else{
                    $session['competition'] = [$model->competition,$profile->type_id]; // include own company in list
                }
            } else{
                return $this->redirect('index');
            }
        }

        // if no session data redirect to index
        if(!isset($session['start_date'])){
            return $this->redirect('index');
        }


        // get brands for the competitor
        $brands = Brand::find()->where(['in','company_id',$session['competition']])->all();

        $brand_list = ArrayHelper::getColumn($brands,'brand_id');

        // load logs
        $logs = OutdoorLogs::find()
            ->joinWith(['bbSite'])
            ->where(['in','brand_id',$brand_list])
            ->andWhere(
                ['between',
                    'date(outdoor_logs.date_time)',
                    $session['start_date'],
                    $session['end_date']
                ])
            ->andFilterWhere([
                'type'=>$session['type'],
                'region_id'=>$session['region']
            ])
            ->orderBy('date_time asc');

        $dataProvider = new ActiveDataProvider([
            'query' => $logs,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // map stuff
        $center = new LatLng(['lat' =>-1.28333, 'lng' => 36.81667]);
        $map = new Map([
            'center' => $center,
            'zoom' => 10,
            'width'=>'100%',
            'height'=> 400 // pixels
        ]);

        // map bounds
        $bounds = new LatLngBounds();
        $markers = [];
        foreach($dataProvider->getModels() as $log){
            // Lets add a marker now
            $title = $log->brand->company->company_name;

            $marker = new Marker([
                'position' => new LatLng(['lat' => $log->lattitude, 'lng' => $log->longitude]),
                'title' => $title,
            ]);

            if($log->brand->company->company_id == $profile->type_id)
                $marker->icon = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';

            // Provide a shared InfoWindow to the marker
            $photo = \Yii::$app->params['imageUrl'].$log->photo;
            $marker->attachInfoWindow(
                new InfoWindow([
                    'content' => '<h5>'.$title.'</h5><img width="100" src="'.$photo.'">'
                ])
            );

            // Add marker to the map
            $map->addOverlay($marker);
            $markers[] = $marker; 
        }

        $center_bounds = $bounds->getBoundsOfMarkers($markers);
        $map->center = $center_bounds->getCenterCoordinates(); // center map accordingly
        $map->zoom = $center_bounds->getZoom(400,10); // min width and zoom level

        return $this->render('result',[
            'dataProvider'=>$dataProvider,
            'map'=>$map
        ]);
    }
}
