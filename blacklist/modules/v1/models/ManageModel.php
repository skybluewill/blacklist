<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\v1\models;
use yii\base\Model;
use app\modules\v1\ResponseResult;
use \yii\data\ArrayDataProvider;
use app\modules\v1\tools\ArrayDataCustomerPaginationProvider;

/**
 * Description of ManageModel
 *
 * @author LXY
 */
class ManageModel extends Model {
    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
    }
    
    /**
     * 显示各公司列表
     * @return \yii\data\ArrayDataProvider
     */
    public function indexCompany() {
        $tempResult = [];
        //分页
        $query = CompanyList::find();
        $pagination = new \yii\data\Pagination(['totalCount'=>$query->count(), 'pageSize' =>10]);
        
        //$query ->offset($pagination ->offset) ->limit($pagination ->limit);
        //echo $query ->prepare(\Yii::$app ->db ->queryBuilder) ->createCommand() ->getRawSql();exit;
        //转换查询出来的数据
        $models = $query ->offset($pagination ->offset) ->limit($pagination ->limit) ->all();
        foreach($models as $model) {
            switch ($model ->is_show_reporter) {
                case CompanyList::SHOW_REPORTER : $model ->reporter = $model ->guest ->nickname;break;
                case CompanyList::NOT_SHOW_REPORTER : $model ->reporter = CompanyList::ANONYMOUS;break;
            }
            switch ($model ->is_verify) {
                case CompanyList::VERIFY : $model ->is_verify = '已审核';break;
                case CompanyList::NOT_VERIFY : $model ->is_verify = '未审核';break;
            }
            switch ($model ->is_show) {
                case CompanyList::SHOW : $model ->is_show = '显示';break;
                case CompanyList::NOT_SHOW : $model ->is_show = '隐藏';break;
            }
            $tempResult[] = $model ->toArray();
        }
        //var_dump($tempResult);exit;
        
        $result = new ArrayDataCustomerPaginationProvider([
            'allModels' => $tempResult,
            'pagination' => $pagination,
        ]);
        //写到这里，个人觉得model里只应该对数据进行处理，记录日志，对数据进行二次包装，应该在控制器里 2017/3/21
        return $result;
    }
    
    /**
     * 提交黑名单条目 逻辑：1.创建用户信息，2.创建公司条目信息，3.创建公司评论。任何一步失败，回滚数据
     */
    public function createCompany() {
         //获取POST数据
       $data = \Yii::$app ->request ->post();
       $data['is_show_reporter'] = $data['anonymous'] ? 0 : 1;
       
       //检查提交公司条目是否存在数据库中
       $companyName = $data['name'];
       if(CompanyList::findOne(['name' => $companyName])) {
           return (new ResponseResult()) ->result(ResponseResult::WARNING, ResponseResult::REASON['sameCompany']);
       }
       
       //开启事务
       $transaction = \Yii::$app ->db ->beginTransaction();
       //创建用户
       $guest = Guest::findOne(['contact_method'=>$data['contact_method'], 'contact'=>$data['contact']]);
       
       if(!$guest) {
           $guest = new Guest();
           if(!$guest ->createGuest($data)) {
                return (new ResponseResult()) ->result(ResponseResult::ERROR, ResponseResult::REASON['createUserFault']) ;
            }
            return (new ResponseResult()) ->result(ResponseResult::WARNING, ResponseResult::REASON['notFindUser']) ;
        }
       
        //创建公司条目
        $company = new CompanyList();
        $company ->reporter = $guest ->id;   //谁创建的公司条目
        if($company ->createCompany($data)) {
            $comment = new Comment();
            $comment ->pid = $company ->id;      //把评论关联到相应的公司条目
            $comment ->reporter = $guest ->id;   //把评论关联到用户
            //创建评论
            if($comment ->createComment($data)) {
               $transaction ->commit();
                \Yii::info(ResponseResult::REASON['succ']);
                return (new ResponseResult()) ->result(ResponseResult::SUCCESS, ResponseResult::REASON['succ']) ;
            }
            $transaction ->rollBack();
            \Yii::error($comment ->getErrors());
            return (new ResponseResult()) ->result(ResponseResult::ERROR, $comment ->getErrors()) ;
        }
        $transaction ->rollBack();
        \Yii::error($company ->getErrors());
        return (new ResponseResult()) ->result(ResponseResult::ERROR, $company ->getErrors()) ;
    }
    
    /**
     * 对公司条目的更改
     * @return array
     */
    public function updateCompany() {
        //获取POST过来的数据
        $data = array_merge(\Yii::$app ->request ->post(), \Yii::$app ->request ->getQueryParams());
        //检测时候传递了制定的属性        
        $continue = array_key_exists('id', $data);
        $continue =  $continue && array_key_exists('name', $data);
        $continue =  $continue && array_key_exists('contact_method', $data);
        $continue =  $continue && array_key_exists('contact', $data);
        //如果所需属性缺少，停止处理
        if(!$continue) {
            return [];
        }
        
        //如果要更新的公司条目不存在，则终止
        $company = CompanyList::findOne(['id' => $data['id']]);
        if(!$company) {
            return ['error' => 4]; 
        }
        //来宾处理规则， 联系方式和联系号码对上则可以改
        if(\Yii::$app ->user ->isGuest) {
                $guest = $company ->guest;
                if($guest ->contact_method == $data['contact_method'] && $guest ->contact == $data['contact']){
                    $company ->load($data, '');
                    if($company ->save(true, ['name'])) {
                        return ['error' => 0];
                    }                    
                }
                return ['error' => 3];
            return ['error' => 2];
        }
        //登陆后的更新方式
        $company ->load($data, '');
        if($company ->save()) {
            return ['error' => 0];
        }        
        return ['error' => 1];
    }
    
    /**
     * 审核公司条目
     */
    public function verifyCompany() {
        //获取BODY过来的数据
        $data = \Yii::$app ->request ->post();
        //检测是否有权限和指定的数据
        if(\Yii::$app ->user ->isGuest && !isset($data['verify'])) {
            \Yii::error('审核没通过验证');
            return ['error' =>1];
        }
        //是否有指定数据
        if($query = \Yii::$app ->request ->getQueryParam('id')) {
            if($company = CompanyList::findOne($query)) {
                $company ->is_verify = $data['verify'];
                if($company ->update()) {
                    \Yii::info('更改审核状态成功');
                    return ['error' =>0];
                }
                \Yii::error('更改审核状态失败');
                return ['error' =>1];
            }
            \Yii::error('查不到指定审核数据');
            return ['error' =>1];
        }
        \Yii::error('缺失id');
        return ['error' =>1];
    }
    
    /**
     * 指定公司条目是否是显示状态
     */
    public function showCompany() {
        //获取BODY过来的数据
        $data = \Yii::$app ->request ->post();
        //检测是否有权限和指定的数据
        if(\Yii::$app ->user ->isGuest && !isset($data['show'])) {
            \Yii::error('显示没通过验证');
            return ['error' =>1];
        }
        //是否有指定数据
        if($query = \Yii::$app ->request ->getQueryParam('id')) {
            if($company = CompanyList::findOne($query)) {
                $company ->is_show = $data['show'];
                if($company ->update()) {
                    \Yii::info('更改显示状态成功');
                    return ['error' =>0];
                }
                \Yii::error('更改显示状态失败');
                return ['error' =>1];
            }
            \Yii::error('查不到指定显示数据');
            return ['error' =>1];
        }
        \Yii::error('缺失id');
        return ['error' =>1];
    }
    
    /**
     * 显示公司条目详情
     */
    public function detailCompany() {
        if($query = \Yii::$app ->request ->getQueryParam('id')) {
            if($company = CompanyList::findOne(['id' =>$query, 'is_show' => CompanyList::SHOW])) {
                $result = $company ->toArray(['id','name','create_date']);
                if($company ->is_show_reporter == CompanyList::SHOW_REPORTER) {
                    $result['reporter'] = $company ->guest ->toArray(['nickname'])['nickname'];
                } else {
                    $result['reporter'] = CompanyList::ANONYMOUS;
                }
                
                foreach ($company ->comments as $comment) {
                    $reporter = $comment ->commentReporter ->toArray(['id', 'nickname']);
                    $tempComment[0] = $comment ->toArray(['comment', 'create_date']);
                    $tempComment[0]['reporter'] = $reporter;
                    $result['comments'][] = $tempComment;
                }
                \Yii::error('查询到公司条目详情');
                return ['error' =>$result];
            }
            \Yii::error('查不到指定审核数据');
            return ['error' =>1];
        }
    }
    
}
