<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "raw_logs".
 *
 * @property integer $raw_id
 * @property string $agent_id
 * @property integer $bb_co_id
 * @property string $lattitude
 * @property string $longitude
 * @property string $date_time
 * @property string $campaign
 * @property string $photo
 * @property string $entry_time
 * @property integer $condition
 * @property string $comment
 * @property integer $status
 */
class RawLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'raw_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bb_co_id', 'condition', 'status'], 'integer'],
            [['campaign', 'comment'], 'string'],
            [['entry_time'], 'safe'],
            [['condition'], 'required'],
            [['agent_id', 'lattitude', 'longitude', 'date_time', 'photo'], 'string', 'max' => 200],
            [['photo'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'raw_id' => 'Raw ID',
            'agent_id' => 'Agent ID',
            'bb_co_id' => 'Bb Co ID',
            'lattitude' => 'Lattitude',
            'longitude' => 'Longitude',
            'date_time' => 'Date Time',
            'campaign' => 'Campaign',
            'photo' => 'Photo',
            'entry_time' => 'Entry Time',
            'condition' => 'Condition',
            'comment' => 'Comment',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBbcondition()
    {
        return $this->hasOne(BillboardCondition::className(), ['id' => 'condition']);
    }
}
