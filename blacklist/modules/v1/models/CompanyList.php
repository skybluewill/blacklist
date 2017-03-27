<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for table "company_list".
 *
 * @property string $id
 * @property string $name 公司名称
 * @property string $reporter 举报人id，和nickname表id字段关联
 * @property string $create_date 创建条目的日期
 * @property int $is_show_reporter 是否显示创建条目人的昵称tinyint 1.显示 0.匿名
 * @property int $is_verify 是否审核 tinyint 1.未审核 2.审核 （也许会有半审核之类的，所以不用0,1表示）
 * @property int $is_show 0.不显示 1.显示
 *
 * @property Comment[] $comments
 */
class CompanyList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_list';
    }
    
    const SHOW = 1;
    const NOT_SHOW = 0;
    const SHOW_REPORTER = 1;
    const NOT_SHOW_REPORTER = 0;
    const ANONYMOUS = '匿名';
    const VERIFY = 2;
    const NOT_VERIFY = 1;
    
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_CREATE = 'create';

    /*public function scenarios()
    {
        $parentScenarios = parent::scenarios();
        return array_merge($parentScenarios, [
            //self::SCENARIO_LOGIN => ['username', 'password'],
            self::SCENARIO_CREATE => ['username', 'email', 'password'],
        ]);
    }*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_date'], 'default', 'value' =>function($model, $attribute) {
                return date('Y-m-d');
            }],
            [['name', 'reporter', 'create_date', 'is_show_reporter',], 'required'],
            [['reporter', 'is_show_reporter', 'is_verify', 'is_show'], 'integer'],
            [['create_date'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [[ 'is_verify', 'is_show'], 'default', 'value' => 0],
            [['is_verify'], 'in', 'range' =>[1,2]],
            [['is_show_reporter','is_show'], 'in', 'range' =>[0,1]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'reporter' => 'Reporter',
            'create_date' => 'Create Date',
            'is_show_reporter' => 'Is Show Reporter',
            'is_verify' => 'Is Verify',
            'is_show' => 'Is Show',
        ];
    }
    
    public function beforeValidate() {
        if(!parent::beforeValidate()) {
            return false;
        }
        $this ->is_verify = 1;
        $this ->is_show = 0;
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['pid' => 'id']);
    }
    
    public function getGuest(){
        return $this ->hasOne(Guest::className(), ['id' => 'reporter']);
    }
    
    /**
     * 
     * @param array $data   创建公司需要的数据
     */
    public function createCompany(array $data) {
        if($this ->load($data, '') && $this ->save()) {
            return true;
        }
        return false;
    }

    
}
