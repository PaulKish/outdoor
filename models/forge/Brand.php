<?php

namespace app\models\forge;

use Yii;

/**
 * This is the model class for table "brand_table".
 *
 * @property integer $brand_id
 * @property integer $agency_id
 * @property string $brand_name
 * @property string $brand_description
 * @property integer $industry_id
 * @property integer $sub_industry_id
 * @property integer $company_id
 * @property integer $country_id
 * @property string $status
 * @property string $entry_date
 * @property integer $segment
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand_table';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agency_id', 'brand_description'], 'required'],
            [['agency_id', 'industry_id', 'sub_industry_id', 'company_id', 'country_id', 'segment'], 'integer'],
            [['brand_description'], 'string'],
            [['entry_date'], 'safe'],
            [['brand_name'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 1],
            [['brand_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'brand_id' => 'Brand ID',
            'agency_id' => 'Agency ID',
            'brand_name' => 'Brand',
            'brand_description' => 'Brand Description',
            'industry_id' => 'Industry ID',
            'sub_industry_id' => 'Sub Industry ID',
            'company_id' => 'Company ID',
            'country_id' => 'Country ID',
            'status' => 'Status',
            'entry_date' => 'Entry Date',
            'segment' => 'Segment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(ForgeUsers::className(), ['company_id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubIndustry()
    {
        return $this->hasOne(SubIndustry::className(), ['auto_id' => 'sub_industry_id']);
    }
}
