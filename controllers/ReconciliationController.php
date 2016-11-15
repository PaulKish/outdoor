<?php

namespace app\controllers;

use app\models\ReconciliationFilterForm;
use app\models\OutdoorLogs;
use app\models\BillboardType;
use app\models\BillboardSites;
use app\models\BillboardCondition;
use app\models\Counties;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class ReconciliationController extends \yii\web\Controller
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

    /**
     * Shows filter form 
     */
    public function actionIndex()
    {
        $model = new ReconciliationFilterForm();
        $types = BillboardType::find()->all();
        $conditions = BillboardCondition::find()->all();
        $regions = Counties::find()->all();

        return $this->render('index', [
            'model' => $model,
            'types' => $types,
            'conditions' => $conditions,
            'regions' => $regions
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
                $session['condition'] = $model->condition;
                $session['type'] = $model->type;
                $session['region'] = $model->region;

                $logs = OutdoorLogs::find()
                    ->joinWith(['bbSite','rawLog'])
                    ->where(['outdoor_logs.bb_co_id'=>$profile->type_id])
                    ->andWhere(['between','outdoor_logs.date_time',$session['start_date'],$session['end_date']])
                    ->andWhere([
                        'type'=>$session['type'],
                        'condition'=> $session['condition'],
                        'region'=>$session['region']
                    ])
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
                ->joinWith(['bbSite','rawLog'])
                ->where(['outdoor_logs.bb_co_id'=>$profile->type_id])
                ->andWhere(['between','outdoor_logs.date_time',$session['start_date'],$session['end_date']])
                ->andWhere([
                    'type'=>$session['type'],
                    'condition'=> $session['condition'],
                    'region'=>$session['region']
                ])
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
