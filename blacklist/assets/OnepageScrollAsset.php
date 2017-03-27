<?php

namespace frontend\assets;

use yii\web\AssetBundle;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 单页面小部件
 *
 * @author LXY
 */
class OnepageScrollAsset extends AssetBundle {
    //put your code here
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'onepage-scroll/css/site.css',
    ];
    public $js = [
        'onepage-scroll/js/jquery.onepage-scroll.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
