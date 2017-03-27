<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\assets;

use yii\base\Widget;
use frontend\assets\OnepageScrollAsset;
use yii\bootstrap\Html;
/**
 * Description of OnepageScroll
 *
 * @author LXY
 */
class OnepageScroll extends Widget {
    
    public $id = 'main';
    
    //put your code here
    public function init() {  
        parent::init();
        echo Html::beginTag('div', ['class'=>'main','id' => $this ->id]);
        echo Html::beginTag('section');
    }
    
    public function run() {  
        echo Html::endTag('section');
        echo Html::beginTag('div');
        $view = $this->getView();  
        /*$this->attributes['id']=$this->options['id'];  
        if($this->hasModel()){  
            $input=Html::activeTextarea($this->model, $this->attribute,$this->attributes);  
        }else{  
            $input=Html::textarea($this->name,'',$this->attributes);  
        }  
        echo $input;*/
        OnepageScrollAsset::register($view);//将Ueditor用到的脚本资源输出到视图
        $js = '$(".main").onepage_scroll({
                sectionContainer: "section",     
                easing: "ease",                  // Easing options accepts the CSS3 easing animation such "ease", "linear", "ease-in",
                                                 // "ease-out", "ease-in-out", or even cubic bezier value such as "cubic-bezier(0.175, 0.885, 0.420, 1.310)"
                animationTime: 1000,             // AnimationTime let you define how long each section takes to animate
                pagination: true,                // You can either show or hide the pagination. Toggle true for show, false for hide.
                updateURL: false,                // Toggle this true if you want the URL to be updated automatically when the user scroll to each page.
                beforeMove: function(index) {},  // This option accepts a callback function. The function will be called before the page moves.
                afterMove: function(index) {},   // This option accepts a callback function. The function will be called after the page moves.
                loop: false,                     // You can have the page loop back to the top/bottom when the user navigates at up/down on the first/last page.
                keyboard: true,                  // You can activate the keyboard controls
                responsiveFallback: false,        // You can fallback to normal page scroll by defining the width of the browser in which
                                                 // you want the responsive fallback to be triggered. For example, set this to 600 and whenever

                direction: "vertical"            // You can now define the direction of the One Page Scroll animation. Options available are "vertical" and "horizontal". The default value is "vertical".  
             });';
        $view->registerJs($js, $view::POS_END);
        //$js='var ue = UE.getEditor("'.$this->options['id'].'",'.$this->getOptions().');';//Ueditor初始化脚本  
        //$view->registerJs($js, $view::POS_END);//将Ueditor初始化脚本也响应到视图中  
    }
}
