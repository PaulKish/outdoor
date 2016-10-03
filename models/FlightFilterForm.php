<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * FlightFilter is the model behind the flight filter form.
 *
 */
class FlightFilterForm extends Model
{
    public $brand;
    public $start_date;
    public $end_date;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['brand', 'start_date', 'end_date'], 'required'],
        ];
    }
}
