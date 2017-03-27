<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for table "guest".
 *
 * @property string $id 表示guest id,用于其它表
 * @property string $nickname 昵称
 * @property string $contact_method char(10)联系方式，如qq，电话，e-mail
 * @property string $contact char(50)联系号码
 */
class Guest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [            
            [['nickname', 'contact_method', 'contact'], 'required'],
            [['nickname'], 'string', 'max' => 20],
            [['contact_method'], 'string', 'max' => 10],
            [['contact'], 'string', 'max' => 50],
            [['contact_method', 'contact'], 'unique', 'targetAttribute' => ['contact_method', 'contact']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => 'Nickname',
            'contact_method' => 'Contact Method',
            'contact' => 'Contact',
        ];
    }
    
    public function getCompanyLists() {
        return $this ->hasMany(CompanyList::className(), ['reporter' => 'id']);
    }
    
    public function createGuest(array $data) {
        if($this ->load($data, '') && $this ->save()) {
            return true;
        }
        return false;
    }
}
