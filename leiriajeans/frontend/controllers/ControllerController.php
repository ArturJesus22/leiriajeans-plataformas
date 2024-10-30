<?php

namespace frontend\controllers;

class ControllerController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
