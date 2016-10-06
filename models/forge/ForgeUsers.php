<?php

namespace app\models\forge;

use Yii;

/**
 * This is the model class for table "user_table".
 *
 * @property integer $company_id
 * @property string $company_name
 * @property string $company_rep_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $last_auto_password
 * @property string $description
 * @property string $registerDate
 * @property string $lastvisitDate
 * @property string $activation
 * @property integer $level
 * @property integer $is_client
 * @property string $usertype
 * @property integer $master_id
 * @property string $login
 * @property string $trp
 * @property string $picture
 * @property string $show_rate
 * @property string $pofemail
 * @property integer $rpts_only
 * @property integer $agency_id
 * @property integer $user_status
 * @property integer $plus_status
 */
class ForgeUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_table';
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
            [['description', 'trp', 'picture', 'agency_id'], 'required'],
            [['registerDate', 'lastvisitDate'], 'safe'],
            [['level', 'is_client', 'master_id', 'rpts_only', 'agency_id', 'user_status', 'plus_status'], 'integer'],
            [['company_name', 'company_rep_name', 'username', 'email', 'password', 'description', 'picture'], 'string', 'max' => 100],
            [['last_auto_password'], 'string', 'max' => 30],
            [['activation', 'usertype', 'login', 'trp', 'show_rate', 'pofemail'], 'string', 'max' => 1],
            [['company_name', 'company_rep_name'], 'unique', 'targetAttribute' => ['company_name', 'company_rep_name'], 'message' => 'The combination of Company Name and Company Rep Name has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'company_id' => 'Company ID',
            'company_name' => 'Company',
            'company_rep_name' => 'Company Rep Name',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'last_auto_password' => 'Last Auto Password',
            'description' => 'Description',
            'registerDate' => 'Register Date',
            'lastvisitDate' => 'Lastvisit Date',
            'activation' => 'Activation',
            'level' => 'Level',
            'is_client' => 'Is Client',
            'usertype' => 'Usertype',
            'master_id' => 'Master ID',
            'login' => 'Login',
            'trp' => 'Trp',
            'picture' => 'Picture',
            'show_rate' => 'Show Rate',
            'pofemail' => 'Pofemail',
            'rpts_only' => 'Rpts Only',
            'agency_id' => 'Agency ID',
            'user_status' => 'User Status',
            'plus_status' => 'Plus Status',
        ];
    }
}
