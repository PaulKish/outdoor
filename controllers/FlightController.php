<?php

namespace app\controllers;

use app\models\FlightFilterForm;
use app\models\OutdoorLogs;
use app\models\forge\Brand;
use yii\data\ActiveDataProvider;

class FlightController extends \yii\web\Controller
{
    /**
     *  Index page, shows filter form
     */
    public function actionIndex()
    {
        $model = new FlightFilterForm();

        // profile
        $profile = \Yii::$app->user->identity->profile;

        // get brands for respective company
        $brands = Brand::find()->where(['company_id'=>$profile->type_id])->all();

        return $this->render('index', [
            'model' => $model,
            'brands'=>$brands
        ]);
    }

    /**
     *  Results page, shows grid result
     */
    public function actionResult(){

        $model = new FlightFilterForm();
        $session = \Yii::$app->session;

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {

                // store model params in session
                $session['start_date'] = $model->start_date;
                $session['end_date'] = $model->end_date;
                $session['brand'] = $model->brand;

                $logs = OutdoorLogs::find()
                    ->where(['in','brand_id',$model->brand])
                    ->andWhere(['between','date_time',$model->start_date,$model->end_date]);

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
                ->where(['in','brand_id',$session['brand']])
                ->andWhere(['between','date_time',$session['start_date'],$session['end_date'] ]);

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