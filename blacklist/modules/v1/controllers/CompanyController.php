<?php
namespace app\modules\v1\controllers;

use yii\rest\Controller;
use yii\rest\ActiveController;
use app\modules\v1\models\CompanyList;
use app\modules\v1\models\Guest;
use app\modules\v1\models\Comment;
use app\modules\v1\models\ManageModel;
use yii\rest\Serializer;
use app\modules\v1\ResponseResult;
/**
 * Description of CompanyController
 *
 * @author LXY
 */
class CompanyController extends Controller {
    /*public function actions()
   {
       $actions = parent::actions();
       // 注销系统自带的实现方法
       //unset($actions['index'], $actions['update'], $actions['create']);
       return $actions;
   }*/
    
    /**
     * @inheritdoc
     */
    public function verbs() {
        return array_merge(parent::verbs(), [
            'index' => ['GET'],
            'view'  => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'delete' => ['DELETE'],
        ]); 
    }
    

    public function actionIndex()
    {
       $model = new ManageModel();
       $data = $model ->indexCompany();
       //var_dump($data);exit;
       $serializer = new Serializer(['collectionEnvelope' => 'companies']);
       $result = $serializer ->serialize($data);
       return (new ResponseResult()) ->result(ResponseResult::SUCCESS, ResponseResult::REASON['succ'], $result) ;
    }
   
    /**
     * 提交黑名单条目 逻辑：1.创建用户信息，2.创建公司条目信息，3.创建公司评论。任何一步失败，回滚数据
     * @return 
     */
    /*public function actionCreate()
    {
        //获取POST数据
       $data = \Yii::$app ->request ->post();
       $data['is_show_reporter'] = $data['anonymous'] ? 0 : 1;
       
       //检查提交公司条目是否存在数据库中
       $companyName = $data['name'];
       if(CompanyList::findOne(['name' => $companyName])) {
           return ['error' => '已有相同公司条目'];
       }
       
       //创建用户
       $guest = new Guest();
       if($guest ->load($data, '') && $guest ->validate()) {
           //开启事务
           $transaction = \Yii::$app ->db ->beginTransaction();
           if(!$guest ->save(false)) {
               $transaction ->rollBack();
               \Yii::error('保存用户失败');
               return ['error' => 1];
           }
           //创建公司条目
           $company = new CompanyList();
           $company ->reporter = $guest ->id;   //谁创建的公司条目
           if($company ->load($data, '') && $company ->save()) {
               $comment = new Comment();
               $comment ->pid = $company ->id;      //把评论关联到相应的公司条目
               $comment ->reporter = $guest ->id;   //把评论关联到用户
               //创建评论
               if($comment ->load($data, '') && $comment ->save()) {
                  $transaction ->commit();
                \Yii::trace('保存成功');
                 return ['error' => 0]; 
               }
               $transaction ->rollBack();
               \Yii::error('保存评论失败');
                return ['error' => $comment ->getErrors()];
           }
           $transaction ->rollBack();
           \Yii::error('保存条目失败');
            return ['error' => $company ->getErrors()];
       }       
       \Yii::error('保存用户失败');
       return ['error' => $guest ->getErrors()];
       
    }*/
    
    /**
     * 调用ManageModel模型的createCompany
     * @return array
     */
    public function actionCreate() {
        $model = new ManageModel();
        return $model ->createCompany();
    }
    
    /**
     * PUT id
     * 
     */
    public function actionUpdate() {
        $model = new ManageModel();
        if(\Yii::$app ->request ->getBodyParam('verify')) {
            return $model ->verifyCompany();
        }
        return $model ->updateCompany();
    }
    
    /**
     * GET id
     */
    public function actionView() {
        $model = new ManageModel();
        return $model ->detailCompany();
    }
}
