<?php

namespace app\models\forge;

use Yii;

/**
 * This is the model class for table "industry".
 *
 * @property integer $industry_id
 * @property string $industry_name
 * @property integer $industry_hash
 */
class Industry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'industry';
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
            [['industry_hash'], 'required'],
            [['industry_hash'], 'integer'],
            [['industry_name'], 'string', 'max' => 100],
            [['industry_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'industry_id' => 'Industry ID',
            'industry_name' => 'Industry Name',
            'industry_hash' => 'Industry Hash',
        ];
    }
}
