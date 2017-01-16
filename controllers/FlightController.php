<?php

namespace app\controllers;

use app\models\FlightFilterForm;
use app\models\BillboardType;
use app\models\BillboardSites;
use app\models\BillboardCondition;
use app\models\Counties;
use app\models\OutdoorLogs;
use app\models\forge\Brand;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class FlightController extends \yii\web\Controller
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
     *  Index page, shows filter form
     */
    public function actionIndex()
    {
        $model = new FlightFilterForm();

        // profile
        $profile = \Yii::$app->user->identity->profile;

        // get brands for respective company
        $brands = Brand::find()->where(['company_id'=>$profile->type_id])->all();

        $types = BillboardType::find()->all();
        $conditions = BillboardCondition::find()->all();
        $regions = Counties::find()->all();

        return $this->render('index', [
            'model' => $model,
            'brands'=> $brands,
            'types' => $types,
            'conditions' => $conditions,
            'regions' => $regions
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
                $session['condition'] = $model->condition;
                $session['type'] = $model->type;
                $session['region'] = $model->region;
            }else{
                return $this->redirect('index');
            }
        }

        // if no session data redirect to index
        if(!isset($session['start_date'])){
            return $this->redirect('index');
        }

        // load logs
        $logs = OutdoorLogs::find()
            ->joinWith(['bbSite','rawLog'])
            ->where(['in','brand_id',$session['brand']])
            ->andWhere(
                ['between',
                    'outdoor_logs.date_time',$session['start_date'],$session['end_date'] 
                ])
             ->andFilterWhere([
                'type'=>$session['type'],
                'condition'=> $session['condition'],
                'region_id'=>$session['region']
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
}