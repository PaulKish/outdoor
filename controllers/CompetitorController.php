<?php

namespace app\controllers;

use app\models\CompetitorFilterForm;
use app\models\OutdoorLogs;
use app\models\forge\IndustryReport;
use app\models\forge\Brand;
use app\models\forge\SubIndustry;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class CompetitorController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$model = new CompetitorFilterForm();

        // profile, get cuser type 
        $profile = \Yii::$app->user->identity->profile;

        // get company industries
        $industry = IndustryReport::find()->where(['company_id'=>$profile->type_id])->all();


        return $this->render('index', [
            'model' => $model,
            'industry'=>$industry
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

                // get subindustries under industry
                $sub_industry = SubIndustry::find()
                    ->where(['industry_id'=>$session['industry']])->all();

                // convert sub industry result to a nice list
                $sub_industry_list = ArrayHelper::getColumn($sub_industry, 'auto_id');

                // join with brands table to select relevant brands
                $logs = OutdoorLogs::find()
                    ->joinWith(['brand' => function($query) use ($sub_industry_list,$profile) { 
                        return $query->from('forgedb.'.Brand::tablename())
                            ->where(['in','sub_industry_id',$sub_industry_list])
                            ->andWhere(['!=','company_id',$profile->type_id]) // exclude own company
                            ->all(); 
                    }])
                    ->where(['between','date_time',$session['start_date'],$session['end_date']]);

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
            // get subindustries under industry
            $sub_industry = SubIndustry::find()
                ->where(['industry_id'=>$session['industry']])->all();

            // convert sub industry result to a nice list
            $sub_industry_list = ArrayHelper::getColumn($sub_industry, 'auto_id');

            // join with brands table to select relevant brands
            $logs = OutdoorLogs::find()
                ->joinWith(['brand' => function($query) use ($sub_industry_list,$profile) { 
                    return $query->from('forgedb.'.Brand::tablename())
                        ->where(['in','sub_industry_id',$sub_industry_list])
                        ->andWhere(['!=','company_id',$profile->type_id]) // exclude own company
                        ->all(); 
                }])
                ->where(['between','date_time',$session['start_date'],$session['end_date']]);

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
