<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ComplianceFilterForm is the model behind the competitor filter form.
 *
 */
class ComplianceFilterForm extends Model
{
    public $count;
    public $start_date;
    public $end_date;
    public $billboard_company;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['count','start_date', 'end_date'], 'required'],
            [['billboard_company'],'safe']
        ];
    }
}