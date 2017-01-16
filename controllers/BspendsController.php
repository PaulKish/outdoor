<?php

namespace app\controllers;

use app\models\CompetitorFilterForm;
use app\models\OutdoorLogs;
use app\models\forge\IndustryReport;
use app\models\forge\Brand;
use app\models\forge\SubIndustry;
use app\models\Counties;
use app\models\BillboardType;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class BspendsController extends \yii\web\Controller
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
    
    public function actionIndex()
    {
    	$model = new CompetitorFilterForm();

        // profile, get cuser type 
        $profile = \Yii::$app->user->identity->profile;

        $types = BillboardType::find()->all();
        $regions = Counties::find()->all();


        return $this->render('index', [
            'model' => $model,
            'types'=>$types,
            'regions'=>$regions
        ]);
    }

    /**
     *  Show grid of results
     */
    public function actionResult(){
        $model = new CompetitorFilterForm();
        $session = \Yii::$app->session;
        $profile = \Yii::$app->user->identity->profile;

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                // store model params in session
                $session['start_date'] = $model->start_date;
                $session['end_date'] = $model->end_date;
                $session['industry'] = $model->industry;
                $session['type'] = $model->type;
                $session['region'] = $model->region;
            }else{
                return $this->redirect('index');
            }

        }
        
        if(!isset($session['start_date'])){
            return $this->redirect('index');
        }

        // join with brands table to select relevant brands
        $logs = OutdoorLogs::find()
            ->select(['outdoor_logs.brand_id,sum(billboard_sites.rate) as total'])
            ->joinWith(['bbSite','brand'=>
                function($query) use ($profile) { 
                    return $query->from('forgedb.'.Brand::tablename())->all(); 
                }
            ])
            ->where(['outdoor_logs.bb_co_id'=>$profile->type_id])
            ->andWhere(['between','date(outdoor_logs.date_time)',$session['start_date'],$session['end_date']])
            ->andFilterWhere([
                'type'=>$session['type'],
                'region_id'=>$session['region']
            ])
            ->groupBy(['company_id'])
            ->orderBy('total desc');

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

    /**
     *  By Brand
     */
    public function actionBrand()
    {
        $model = new CompetitorFilterForm();

        // profile, get cuser type 
        $profile = \Yii::$app->user->identity->profile;
        $types = BillboardType::find()->all();
        $regions = Counties::find()->all();

        return $this->render('brand', [
            'model' => $model,
            'types'=>$types,
            'regions'=>$regions
        ]);
    }


    /**
     *  Show grid of results
     */
    public function actionResultBrand(){
        $model = new CompetitorFilterForm();
        $session = \Yii::$app->session;
        $profile = \Yii::$app->user->identity->profile;

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                // store model params in session
                $session['start_date'] = $model->start_date;
                $session['end_date'] = $model->end_date;
                $session['industry'] = $model->industry;
                $session['type'] = $model->type;
                $session['region'] = $model->region;                
            }
            else{
                return $this->redirect('brand');
            }
        }

        if(!isset($session['start_date'])){
            return $this->redirect('index');
        }

        // join with brands table to select relevant brands
        $logs = OutdoorLogs::find()
            ->select(['outdoor_logs.brand_id,sum(billboard_sites.rate) as total'])
            ->joinWith(['brand' => function($query) use ($profile) { 
                return $query->from('forgedb.'.Brand::tablename())->all(); 
            },'bbSite'])
            ->where(['outdoor_logs.bb_co_id'=>$profile->type_id])
            ->andWhere(['between','date(outdoor_logs.date_time)',$session['start_date'],$session['end_date']])
            ->andFilterWhere([
                'type'=>$session['type'],
                'region_id'=>$session['region']
            ])
            ->groupBy(['brand_id'])
            ->orderBy('total desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $logs,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('result-brand',[
            'dataProvider'=>$dataProvider
        ]);
    }
}
