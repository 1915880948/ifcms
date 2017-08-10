<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%account}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $union_id
 * @property string $open_id
 * @property integer $source
 * @property string $token
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'source', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['union_id'], 'string', 'max' => 100],
            [['open_id', 'token'], 'string', 'max' => 50],
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
            'union_id' => 'Union ID',
            'open_id' => 'Open ID',
            'source' => 'Source',
            'token' => 'Token',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
