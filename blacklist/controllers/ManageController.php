<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
/**
 * Description of ManageController
 *
 * @author LXY
 */
class ManageController extends Controller {
    //put your code here
    public $layout = 'manage';
    
    public function actionList() {  
        return $this ->render('index');
    }
    
    public function actionCreate() {  
        return $this ->render('//site/index');
    }
    
    public function actionTest() {  
        var_dump($_FILES);
    }
}
