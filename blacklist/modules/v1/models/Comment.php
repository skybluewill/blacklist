<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property string $pid company的id
 * @property string $comment text 具体评论
 * @property string $reporter int 创建该评论的id（nickname表id）
 * @property string $create_date date 创建日期
 *
 * @property CompanyList $p
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_date'], 'default', 'value' =>function($model, $attribute) {
                return date('Y-m-d');
            }],
            [['pid', 'comment', 'reporter', 'create_date'], 'required'],
            [['pid', 'reporter'], 'integer'],
            [['comment'], 'string'],
            [['create_date'], 'safe'],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyList::className(), 'targetAttribute' => ['pid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pid' => 'Pid',
            'comment' => 'Comment',
            'reporter' => 'Reporter',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyList()
    {
        return $this->hasOne(CompanyList::className(), ['id' => 'pid']);
    }
    
    public function getCommentReporter()
    {
        return $this->hasOne(Guest::className(), ['id' => 'reporter']);
    }
    
    /**
     * 
     * @param array $data   创建公司需要的数据
     */
    public function createComment(array $data) {
        if($this ->load($data, '') && $this ->save()) {
            return true;
        }
        return false;
    }
}
