<?php

namespace app\models\forge;

use Yii;

/**
 * This is the model class for table "industryreport".
 *
 * @property integer $auto_id
 * @property integer $company_id
 * @property integer $industry_id
 */
class IndustryReport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'industryreport';
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
            [['company_id', 'industry_id'], 'required'],
            [['company_id', 'industry_id'], 'integer'],
            [['company_id', 'industry_id'], 'unique', 'targetAttribute' => ['company_id', 'industry_id'], 'message' => 'The combination of Company ID and Industry ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'auto_id' => 'Auto ID',
            'company_id' => 'Company ID',
            'industry_id' => 'Industry ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustry()
    {
        return $this->hasOne(Industry::className(), ['industry_id' => 'industry_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(ForgeUsers::className(), ['company_id' => 'company_id']);
    }
}