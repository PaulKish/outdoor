<?php

namespace app\models;

use Yii;
use app\models\forge\ForgeUsers;
/**
 * This is the model class for table "user_assignment".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_type
 * @property integer $type_id
 *
 * @property User $user
 */
class UserAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_type', 'type_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'user_type' => 'User Type',
            'type_id' => 'Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        if($this->user_type == 1){ // type 1 is advertisers
            return $this->hasOne(ForgeUsers::className(), ['company_id' => 'type_id']);
        }else{ // must be a billboard owner
            return $this->hasOne(BbCompanies::className(), ['co_id' => 'type_id']);
        } 
    }
}
