<?php

namespace app\controllers;

class CompetitorController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionResult()
    {
        return $this->render('result');
    }

}
