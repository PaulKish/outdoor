<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * CompetitorFilterForm is the model behind the competitor filter form.
 *
 */
class CompetitorFilterForm extends Model
{
    public $industry;
    public $start_date;
    public $end_date;
    public $region;
    public $type;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['industry','start_date', 'end_date','region','type'], 'required'],
        ];
    }
}