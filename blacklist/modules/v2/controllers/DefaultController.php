<?php

namespace app\modules\v2\controllers;

use yii\web\Controller;

/**
 * Default controller for the `v2` module
 */
class DefaultController extends Controller
{
    public $defaultAction = 'index';
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
