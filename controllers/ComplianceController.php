<?php

namespace app\controllers;

use app\models\ComplianceFilterForm;
use app\models\BbCompanies;
use app\models\OutdoorLogs;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class ComplianceController extends \yii\web\Controller
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
        $model = new ComplianceFilterForm();

        $bbcompanies = BbCompanies::find()->orderBy('company_name')->all();

        return $this->render('index', [
            'model' => $model,
            'bbcompanies' => $bbcompanies
        ]);
    }

    /**
     *  Result page shows compliance
     * */
    public function actionResult(){
        $model = new ComplianceFilterForm();

        $session = \Yii::$app->session;

        $profile = \Yii::$app->user->identity->profile;

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {

                // store model params in session
                $session['start_date'] = $model->start_date;
                $session['end_date'] = $model->end_date;
                $session['billboard_company'] = $model->billboard_company;
                $session['count'] = $model->count;

                $logs = OutdoorLogs::find()
                    //->joinWith(['bbSite','rawLog'])
                    ->where(['bb_co_id'=>$session['billboard_company']])
                    ->andWhere(
                        ['between',
                            'outdoor_logs.date_time',$session['start_date'],$session['end_date'] 
                        ])
                    /*
                    ->andWhere([
                        'type'=>$session['type'],
                        'condition'=> $session['condition'],
                        'region'=>$session['region']
                    ])*/
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
        }elseif($session['start_date']){ //pull search params from session
            $logs = OutdoorLogs::find()
                //->joinWith(['bbSite','rawLog'])
                ->where(['bb_co_id'=>$session['billboard_company']])
                ->andWhere(
                    ['between',
                        'outdoor_logs.date_time',$session['start_date'],$session['end_date'] 
                    ])
                /*
                ->andWhere([
                    'type'=>$session['type'],
                    'condition'=> $session['condition'],
                    'region'=>$session['region']
                ])*/
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