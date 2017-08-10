<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%consult}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $product_id
 * @property string $question
 * @property string $answer
 * @property string $create_time
 * @property string $update_time
 */
class Consult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%consult}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['question'], 'string', 'max' => 230],
            [['answer'], 'string', 'max' => 300],
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
            'question' => 'Question',
            'answer' => 'Answer',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
