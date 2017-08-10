<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_collect}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $status
 * @property string $create_time
 */
class UserCollect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_collect}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'status'], 'integer'],
            [['create_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'status' => 'Status',
            'create_time' => 'Create Time',
        ];
    }
}
