<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\OutdoorLogs;
use app\models\forge\Brand;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;

class SiteController extends Controller
{
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
        $center = new LatLng(['lat' =>-1.28333, 'lng' => 36.81667]);
        $map = new Map([
            'center' => $center,
            'zoom' => 10,
            'width'=>'100%',
            'height'=> 400 // pixels
        ]);

        // pull logs
        $profile = \Yii::$app->user->identity->profile;
        if($profile->user_type == 1){ // billboards for advertisers
            $logs = OutdoorLogs::find()
            ->joinWith(['brand' => function($query) use ($profile) { 
                return $query->from('forgedb.'.Brand::tablename())
                    ->where(['company_id'=>$profile->type_id]) 
                    ->all(); 
            }])
            ->where('date_time >= now() - interval 1 month')
            ->all();
        }else{ // show billboards for company
            $logs = OutdoorLogs::find()
            ->where('date_time >= now() - interval 1 month')
            ->andWhere(['bb_co_id'=>$profile->type_id])
            ->all();
        }
            

        foreach($logs as $log){
            // Lets add a marker now
            $title = isset($log->brand->brand_name) ? $log->brand->brand_name:'None';
            $title .= ' | '.$log->bbCompany->company_name; 

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
        }

        return $this->render('index',['map'=>$map]);
    }

    /**
     *  Shows a map
     */ 
    public function actionMap(){
        $this->layout = '@app/views/layouts/login';
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
        $this->layout = '@app/views/layouts/login';

        $photo = \Yii::$app->request->get('photo');
        
        return $this->render('photo',[
            'photo'=>$photo,
        ]);
    }
}
