<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "billboard_condition".
 *
 * @property integer $id
 * @property string $condition
 */
class BillboardCondition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billboard_condition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['condition'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'condition' => 'Condition',
        ];
    }
}
