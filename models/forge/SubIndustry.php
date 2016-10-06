<?php

namespace app\models\forge;

use Yii;

/**
 * This is the model class for table "sub_industry".
 *
 * @property integer $auto_id
 * @property integer $industry_id
 * @property string $sub_industry_name
 */
class SubIndustry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sub_industry';
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
            [['industry_id'], 'required'],
            [['industry_id'], 'integer'],
            [['sub_industry_name'], 'string', 'max' => 100],
            [['industry_id', 'sub_industry_name'], 'unique', 'targetAttribute' => ['industry_id', 'sub_industry_name'], 'message' => 'The combination of Industry ID and Sub Industry Name has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'auto_id' => 'Auto ID',
            'industry_id' => 'Industry ID',
            'sub_industry_name' => 'Sub Industry',
        ];
    }
}
