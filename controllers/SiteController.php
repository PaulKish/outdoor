<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

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
        return $this->render('index');
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
