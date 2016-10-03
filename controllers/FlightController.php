<?php

namespace app\controllers;

class FlightController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMap()
    {
        return $this->render('map');
    }

    public function actionPhoto()
    {
        return $this->render('photo');
    }

    public function actionResult()
    {
        return $this->render('result');
    }

}
