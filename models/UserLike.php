<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_like}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $target_id
 * @property integer $type
 * @property string $create_time
 */
class UserLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_like}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'target_id', 'type'], 'integer'],
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
            'target_id' => 'Target ID',
            'type' => 'Type',
            'create_time' => 'Create Time',
        ];
    }
}
