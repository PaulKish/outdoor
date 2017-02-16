<?php

namespace app\controllers;

use app\models\ReconciliationFilterForm;
use app\models\OutdoorLogs;
use app\models\BbCompanies;
use app\models\BillboardType;
use app\models\BillboardSites;
use app\models\BillboardCondition;
use app\models\Counties;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class NccController extends \yii\web\Controller
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
        $types = BillboardType::find()->orderBy('type asc')->all();
        $conditions = BillboardCondition::find()->orderBy('condition asc')->all();
        //$regions = Counties::find()->all();
        $bbcompanies = BbCompanies::find()->orderBy('company_name asc')->all();

        return $this->render('index', [
            'model' => $model,
            'types' => $types,
            'conditions' => $conditions,
            //'regions' => $regions,
            'bbcompanies'=>$bbcompanies
        ]);
    }

    /**
     *  Shows results of filtering in a grid view // needs more optimization
     */
    public function actionResult(){

        $model = new ReconciliationFilterForm();
        //$profile = \Yii::$app->user->identity->profile;
        $session = \Yii::$app->session;

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                // store model params in session so that pagination works
                $session['start_date'] = $model->start_date;
                $session['end_date'] = $model->end_date;
                $session['condition'] = $model->condition;
                $session['type'] = $model->type;
                $session['bbcompany'] = $model->bbcompany;
            }else{
                return $this->redirect('index');
            }
        }

        // if no session data redirect to index
        if(!isset($session['start_date'])){
            return $this->redirect('index');
        }

        $logs = OutdoorLogs::find()
            ->joinWith(['bbSite','rawLog'])
            ->select(['outdoor_logs.*',new \yii\db\Expression("GROUP_CONCAT(outdoor_logs.photo,'__',outdoor_logs.date_time ORDER BY outdoor_logs.date_time DESC) as photos")])
            ->where(['between',
                'date(outdoor_logs.date_time)',
                $session['start_date'],
                $session['end_date']]
                )
            ->andFilterWhere([
                'type'=>$session['type'],
                'condition'=> $session['condition'],
                'outdoor_logs.bb_co_id'=>$session['bbcompany']
            ])
            ->groupBy(new \yii\db\Expression("IFNULL(outdoor_logs.bb_site_id, outdoor_logs.id)"))
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
