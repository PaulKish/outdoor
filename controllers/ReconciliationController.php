<?php

namespace app\controllers;

use app\models\ReconciliationFilterForm;
use app\models\OutdoorLogs;

class ReconciliationController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new ReconciliationFilterForm();

        // profile
        $profile = \Yii::$app->user->identity->profile;

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {

                $logs = OutdoorLogs::find()
                    ->where(['bb_co_id'=>$profile->type_id])
                    ->andWhere(['between','date_time',$model->start_date,$model->end_date]);

                return $this->render('result',[
                    'logs'=>$logs
                ]);
            }
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }
}
