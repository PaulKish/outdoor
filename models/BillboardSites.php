<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "billboard_sites".
 *
 * @property integer $id
 * @property string $site_name
 * @property integer $bb_co_id
 * @property string $region
 * @property string $media_size
 * @property string $faces
 * @property string $Description
 * @property string $lattitude
 * @property string $longitude
 * @property integer $rate
 */
class BillboardSites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billboard_sites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bb_co_id'], 'required'],
            [['bb_co_id', 'rate'], 'integer'],
            [['region'], 'string'],
            [['site_name'], 'string', 'max' => 100],
            [['media_size', 'faces'], 'string', 'max' => 50],
            [['Description', 'lattitude', 'longitude'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_name' => 'Site Name',
            'bb_co_id' => 'Bb Co ID',
            'region' => 'Region',
            'media_size' => 'Media Size',
            'faces' => 'Faces',
            'Description' => 'Description',
            'lattitude' => 'Lattitude',
            'longitude' => 'Longitude',
            'rate' => 'Rate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBbCompany()
    {
        return $this->hasOne(BbCompanies::className(), ['co_id' => 'bb_co_id']);
    }
}
