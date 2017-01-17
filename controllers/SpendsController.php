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

class SpendsController extends \yii\web\Controller
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

        // get company industries
        $industry = IndustryReport::find()->where(['company_id'=>$profile->type_id])->all();
        $sub_industry = SubIndustry::find()
            ->where(['in','industry_id',$industry])->all();
        $types = BillboardType::find()->all();
        $regions = Counties::find()->all();


        return $this->render('index', [
            'model' => $model,
            'industry'=>$sub_industry,
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

        /*
        // if industry is blank
        $industry = IndustryReport::find()->where(['company_id'=>$profile->type_id])->all();
        $industries = ArrayHelper::getColumn($industry,'industry_id'); 

        // get subindustries under industry
        if($model->industry == ''){
            $sub_industry = SubIndustry::find()
            ->where(['in','industry_id',$industries])->all();
        }else{
            $sub_industry = SubIndustry::find()
            ->where(['industry_id'=>$session['industry']])->all();
        }*/
        
        // convert sub industry result to a nice list
        //$sub_industry_list = ArrayHelper::getColumn($sub_industry, 'auto_id');

        // join with brands table to select relevant brands
        $logs = OutdoorLogs::find()
            ->select(['outdoor_logs.brand_id,sum(billboard_sites.rate) as total'])
            ->joinWith(['brand' => function($query) use ($session) { 
                return $query->from('forgedb.'.Brand::tablename())
                    //->where(['in','sub_industry_id',$sub_industry_list])
                    ->filterWhere(['sub_industry_id'=>$session['industry']])
                    ->all(); 
            },'bbSite'])
            ->where(
                ['between',
                    'date(outdoor_logs.date_time)',
                    $session['start_date'],
                    $session['end_date']
                ])
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

        // get company industries
        $industry = IndustryReport::find()->where(['company_id'=>$profile->type_id])->all();
        $sub_industry = SubIndustry::find()
            ->where(['in','industry_id',$industry])->all();
        $types = BillboardType::find()->all();
        $regions = Counties::find()->all();

        return $this->render('brand', [
            'model' => $model,
            'industry'=>$sub_industry,
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

        /*
        // if industry is blank
        $industry = IndustryReport::find()->where(['company_id'=>$profile->type_id])->all();
        $industries = ArrayHelper::getColumn($industry,'industry_id'); 

        // get subindustries under industry
        if($model->industry == ''){
            $sub_industry = SubIndustry::find()
            ->where(['in','industry_id',$industries])->all();
        }else{
            $sub_industry = SubIndustry::find()
            ->where(['industry_id'=>$session['industry']])->all();
        }

        // convert sub industry result to a nice list
        $sub_industry_list = ArrayHelper::getColumn($sub_industry, 'auto_id');

        */
        // join with brands table to select relevant brands
        $logs = OutdoorLogs::find()
            ->select(['outdoor_logs.brand_id,sum(billboard_sites.rate) as total'])
            ->joinWith(['brand' => function($query) use ($session) { 
                return $query->from('forgedb.'.Brand::tablename())
                    //->where(['in','sub_industry_id',$sub_industry_list])
                    ->filterWhere(['sub_industry_id'=>$session['industry']])
                    ->all(); 
            },'bbSite'])
            ->where(
                ['between',
                    'date(outdoor_logs.date_time)',
                    $session['start_date'],
                    $session['end_date']
                ])
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

    /**
     *  By Brand
     */
    public function actionIndustry()
    {
        $model = new CompetitorFilterForm();
        $types = BillboardType::find()->all();
        $regions = Counties::find()->all();

        return $this->render('industry', [
            'model' => $model,
            'types'=>$types,
            'regions'=>$regions
        ]);
    }

    /**
     *  Show grid of results
     */
    public function actionResultIndustry(){
        $model = new CompetitorFilterForm();
        $session = \Yii::$app->session;
        $profile = \Yii::$app->user->identity->profile;
        $industry = IndustryReport::find()->where(['company_id'=>$profile->type_id])->all();
        $model->industry = ArrayHelper::getColumn($industry, 'industry_id');

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                // store model params in session
                $session['start_date'] = $model->start_date;
                $session['end_date'] = $model->end_date;
                $session['industry'] = $model->industry;
                $session['type'] = $model->type;
                $session['region'] = $model->region;
            }else{
                return $this->redirect('industry');
            }
        }
        
        if(!isset($session['start_date'])){
            return $this->redirect('index');
        }

        // get subindustries under industry
        $sub_industry = SubIndustry::find()
            ->where(['in','industry_id',$session['industry']])->all();

        // convert sub industry result to a nice list
        $sub_industry_list = ArrayHelper::getColumn($sub_industry, 'auto_id');

        // join with brands table to select relevant brands
        $logs = OutdoorLogs::find()
            ->select(['outdoor_logs.brand_id,sum(billboard_sites.rate) as total'])
            ->joinWith(['brand' => function($query) use ($sub_industry_list) { 
                return $query->from('forgedb.'.Brand::tablename())
                    ->innerJoin('forgedb.sub_industry', 'sub_industry.auto_id = brand_table.sub_industry_id')
                    ->where(['in','sub_industry_id',$sub_industry_list])
                    ->all(); 
            },'bbSite'])
            ->where(
                ['between',
                    'date(outdoor_logs.date_time)',
                    $session['start_date'],
                    $session['end_date']
                ])
            ->andFilterWhere([
                'type'=>$session['type'],
                'region_id'=>$session['region']
            ])
            ->groupBy(['sub_industry.industry_id'])
            ->orderBy('total desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $logs,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('result-industry',[
            'dataProvider'=>$dataProvider
        ]);
    }
}
