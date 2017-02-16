<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\OutdoorLogs;
use app\models\forge\Brand;
use app\models\Counties;
use app\models\DashFilterForm;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\LatLngBounds;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;

class SiteController extends Controller
{
    public $layout = '@app/views/layouts/login';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    { 
        $regions = Counties::find()->all();
        $model = new DashFilterForm();

        $this->layout = '@app/views/layouts/main';

        $center = new LatLng(['lat' =>-1.28333, 'lng' => 36.81667]);
        $map = new Map([
            'center' => $center,
            'zoom' => 10,
            'width'=>'100%',
            'height'=> 400 // pixels
        ]);

        $region = 47; // hard code to Nairobi
        $model->region = $region;
        if ($model->load(\Yii::$app->request->post())) {
            $region = $model->region;
        }

        // pull logs
        $profile = \Yii::$app->user->identity->profile;

        // means no assignment has taken place
        if($profile == NULL){
            $this->layout = '@app/views/layouts/login';
            return $this->render('account');
        }

        if($profile->user_type == 1){ // billboards for advertisers
            $logs = OutdoorLogs::find()
            ->joinWith(['brand' => function($query) use ($profile) { 
                return $query->from('forgedb.'.Brand::tablename())
                    ->where(['company_id'=>$profile->type_id]) 
                    ->all(); 
            },'bbSite'])
            ->filterWhere(['region_id'=>$region])
            ->limit(20)
            ->distinct()
            ->all();
        }elseif($profile->user_type == 3){ // NCC account
            $logs = OutdoorLogs::find()
            ->joinWith(['bbSite','rawLog'])
            ->filterWhere(['region_id'=>$region,'condition'=>4])
            ->limit(20)
            ->all();
        }else{ // show billboards for company
            $logs = OutdoorLogs::find()
            ->joinWith(['bbSite'])
            ->where(['outdoor_logs.bb_co_id'=>$profile->type_id])
            ->andFilterWhere(['region_id'=>$region])
            ->distinct()
            ->limit(20)
            ->all();
        }
        
        // map bounds
        $bounds = new LatLngBounds();
        $markers = [];
        foreach($logs as $log){
            // Lets add a marker now
            $title = isset($log->brand->brand_name) ? $log->brand->brand_name: 'None';

            $marker = new Marker([
                'position' => new LatLng(['lat' => $log->lattitude, 'lng' => $log->longitude]),
                'title' => $title
            ]);

            // Provide a shared InfoWindow to the marker
            $photo = Yii::$app->params['imageUrl'].$log->photo;
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


        return $this->render('index',['map'=>$map,'model'=>$model,'regions'=>$regions]);
    }

    /**
     *  Shows a map
     */ 
    public function actionMap(){
        $lat = \Yii::$app->request->get('lat');
        $long = \Yii::$app->request->get('long');
        return $this->render('map',[
            'lat'=>$lat,
            'long'=>$long
        ]);
    }

    /**
     *  Shows photo
     */
    public function actionPhoto(){
        $photo = \Yii::$app->request->get('photo');
        return $this->render('photo',[
            'photo'=>$photo,
        ]);
    }
}
