<?php

namespace app\models;

use Yii;
use app\models\forge\Brand;

/**
 * This is the model class for table "outdoor_logs".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property integer $raw_id
 * @property integer $bb_co_id
 * @property integer $bb_site_id
 * @property string $bb_size
 * @property string $lattitude
 * @property string $longitude
 * @property string $date_time
 * @property string $photo
 * @property integer $brand_id
 * @property integer $active
 * @property string $rate
 * @property string $entry_time
 */
class OutdoorLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outdoor_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'raw_id', 'bb_co_id', 'bb_site_id', 'brand_id', 'active'], 'integer'],
            [['bb_size', 'photo'], 'string'],
            [['date_time', 'entry_time'], 'safe'],
            [['lattitude', 'longitude', 'rate'], 'string', 'max' => 200],
            [['raw_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => 'Admin ID',
            'raw_id' => 'Raw ID',
            'bb_co_id' => 'Bb Co ID',
            'bb_site_id' => 'Bb Site ID',
            'bb_size' => 'Bb Size',
            'lattitude' => 'Lattitude',
            'longitude' => 'Longitude',
            'date_time' => 'Date',
            'photo' => 'Photo',
            'brand_id' => 'Brand ID',
            'active' => 'Active',
            'rate' => 'Rate',
            'entry_time' => 'Entry Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBbCompany()
    {
        return $this->hasOne(BbCompanies::className(), ['co_id' => 'bb_co_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBbSite()
    {
        return $this->hasOne(BillboardSites::className(), ['bb_site_id' => 'bb_site_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['brand_id' => 'brand_id']);
    }
}
