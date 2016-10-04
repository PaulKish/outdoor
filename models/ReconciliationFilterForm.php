<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ReconciliationFilterForm is the model behind the reconcilation filter form.
 *
 */
class ReconciliationFilterForm extends Model
{
    public $start_date;
    public $end_date;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['start_date', 'end_date'], 'required'],
        ];
    }
}