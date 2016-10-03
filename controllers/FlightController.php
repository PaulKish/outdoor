<?php

namespace app\controllers;

use app\models\FlightFilterForm;
use app\models\forge\Brand;

class FlightController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new FlightFilterForm();

        // get brands for respective company
        $brands = Brand::find()->where(['company_id'=>38])->limit(20)->all();

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }

        return $this->render('index', [
            'model' => $model,
            'brands'=>$brands
        ]);
    }

    public function actionMap()
    {
        return $this->render('map');
    }

    public function actionPhoto()
    {
        return $this->render('photo');
    }

    public function actionResult()
    {
        return $this->render('result');
    }

}
