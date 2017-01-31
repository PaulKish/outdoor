<?php

namespace app\controllers;

use app\models\FlightFilterForm;
use app\models\BillboardType;
use app\models\BillboardSites;
use app\models\BillboardCondition;
use app\models\Counties;
use app\models\OutdoorLogs;
use app\models\forge\Brand;
use app\models\forge\SubIndustry;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

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

        // get column data
        $sub_industries = ArrayHelper::getColumn($brands,'sub_industry_id'); 
        $sub_industry = SubIndustry::find()
            ->where(['in','auto_id',$sub_industries])->all();

        return $this->render('index', [
            'model' => $model,
            'brands'=> $brands,
            'types' => $types,
            'conditions' => $conditions,
            'regions' => $regions,
            'sub_industry' => $sub_industry
        ]);
    }

    /**
     * List of brands
     **/
    public function actionBrandList($id){

        $profile = \Yii::$app->user->identity->profile;
        $brands = Brand::find()
            ->where(['company_id'=>$profile->type_id,'sub_industry_id'=>$id])
            ->all(); // get all brands under specific sub industry

        $html = '';
        foreach($brands as $list){
            $html .='<div class="checkbox">
                        <label>
                        <input name="FlightFilterForm[brand][]" value="'.$list->brand_id.'" type="checkbox"> '.$list->brand_name.'
                        </label>
                    </div><br>';
        }
        return $html;
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
            ->select(['outdoor_logs.*',new \yii\db\Expression("GROUP_CONCAT(outdoor_logs.photo,'__',outdoor_logs.date_time ORDER BY outdoor_logs.date_time DESC) as photos")])
            ->joinWith(['bbSite','rawLog'])
            ->where(['in','brand_id',$session['brand']])
            ->andWhere(
                ['between',
                    'date(outdoor_logs.date_time)',
                    $session['start_date'],
                    $session['end_date']
                ])
            ->andFilterWhere([
                'type'=>$session['type'],
                'condition'=> $session['condition'],
                'region_id'=>$session['region']
            ])
            ->groupBy('outdoor_logs.bb_site_id','outdoor_logs.brand_id')
            ->orderBy('outdoor_logs.date_time asc');

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