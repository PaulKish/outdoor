<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * DashFilter is the model behind the Dash filter form.
 *
 */
class DashFilterForm extends Model
{
    public $region;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['region'], 'required'],
        ];
    }
}
