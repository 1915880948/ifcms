<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $nickname
 * @property string $portrait
 * @property integer $gender
 * @property string $phone
 * @property string $email
 * @property string $company
 * @property string $province
 * @property string $city
 * @property integer $status
 * @property string $create_time
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender', 'status'], 'integer'],
            [['create_time'], 'safe'],
            [['nickname', 'province', 'city'], 'string', 'max' => 10],
            [['portrait'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 30],
            [['company'], 'string', 'max' => 50],
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
            'portrait' => 'Portrait',
            'gender' => 'Gender',
            'phone' => 'Phone',
            'email' => 'Email',
            'company' => 'Company',
            'province' => 'Province',
            'city' => 'City',
            'status' => 'Status',
            'create_time' => 'Create Time',
        ];
    }
}
