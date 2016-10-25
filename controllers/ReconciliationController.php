<?php

namespace app\controllers;

use app\models\ReconciliationFilterForm;
use app\models\OutdoorLogs;
use yii\data\ActiveDataProvider;

class ReconciliationController extends \yii\web\Controller
{
    /**
     * Shows filter form 
     */
    public function actionIndex()
    {
        $model = new ReconciliationFilterForm();

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     *  Shows results of filtering in a grid view // needs more optimization
     */
    public function actionResult(){

        $model = new ReconciliationFilterForm();
        
        // profile
        $profile = \Yii::$app->user->identity->profile;

        $session = \Yii::$app->session;

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                // store model params in session
                $session['start_date'] = $model->start_date;
                $session['end_date'] = $model->end_date;

                $logs = OutdoorLogs::find()
                    ->where(['bb_co_id'=>$profile->type_id])
                    ->andWhere(['between','date_time',$session['start_date'],$session['end_date']])
                    ->orderBy('date_time asc');

                $dataProvider = new ActiveDataProvider([
                    'query' => $logs,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);

                return $this->render('result',[
                    'dataProvider'=>$dataProvider
                ]);
            }
        }elseif($session['start_date']){

            $logs = OutdoorLogs::find()
                    ->where(['bb_co_id'=>$profile->type_id])
                    ->andWhere(['between','date_time',$session['start_date'],$session['end_date']])
                    ->orderBy('date_time asc');

            $dataProvider = new ActiveDataProvider([
                'query' => $logs,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

            return $this->render('result',[
                'dataProvider'=>$dataProvider
            ]);
        }else{
            return $this->redirect('index');
        }
    }
}
