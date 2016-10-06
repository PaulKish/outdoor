<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bb_companies".
 *
 * @property integer $co_id
 * @property string $company_name
 * @property string $desc
 * @property string $created
 */
class BbCompanies extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bb_companies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['created'], 'safe'],
            [['company_name'], 'string', 'max' => 200],
            [['company_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'co_id' => 'Co ID',
            'company_name' => 'Billboard Company',
            'desc' => 'Desc',
            'created' => 'Created',
        ];
    }
}
