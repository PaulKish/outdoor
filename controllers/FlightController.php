<?php

namespace app\controllers;

use app\models\FlightFilterForm;
use app\models\OutdoorLogs;
use app\models\forge\Brand;
use yii\helpers\VarDumper;

class FlightController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new FlightFilterForm();

        // get brands for respective company
        $brands = Brand::find()->where(['company_id'=>38])->all();

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {

                $logs = OutdoorLogs::find()
                    ->where(['brand_id'=>$model->brand])
                    ->andWhere(['between','date_time',$model->start_date,$model->end_date]);

                return $this->render('result',[
                    'logs'=>$logs
                ]);
            }
        }

        return $this->render('index', [
            'model' => $model,
            'brands'=>$brands
        ]);
    }

    public function actionPhoto()
    {
        return $this->renderPartial('photo');
    }
}
