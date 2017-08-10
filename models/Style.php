<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%style}}".
 *
 * @property integer $id
 * @property string $picture
 * @property string $name_en
 * @property string $name_cn
 * @property integer $sort
 * @property integer $status
 * @property integer $display_status
 * @property integer $shelf_status
 * @property string $create_time
 */
class Style extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%style}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'status', 'display_status', 'shelf_status'], 'integer'],
            [['create_time'], 'safe'],
            [['picture'], 'string', 'max' => 300],
            [['name_en'], 'string', 'max' => 30],
            [['name_cn'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'picture' => 'Picture',
            'name_en' => 'Name En',
            'name_cn' => 'Name Cn',
            'sort' => 'Sort',
            'status' => 'Status',
            'display_status' => 'Display Status',
            'shelf_status' => 'Shelf Status',
            'create_time' => 'Create Time',
        ];
    }
}
