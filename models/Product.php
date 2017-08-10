<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property string $number
 * @property string $picture
 * @property string $picture_height
 * @property string $picture_width
 * @property integer $style_id
 * @property string $style_name_en
 * @property string $style_name_cn
 * @property string $series
 * @property string $tag
 * @property string $detail
 * @property integer $collect_count
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['style_id', 'collect_count', 'status'], 'integer'],
            [['detail'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['number'], 'string', 'max' => 15],
            [['picture'], 'string', 'max' => 200],
            [['picture_height', 'picture_width'], 'string', 'max' => 5],
            [['style_name_en', 'style_name_cn'], 'string', 'max' => 10],
            [['series'], 'string', 'max' => 50],
            [['tag'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'picture' => 'Picture',
            'picture_height' => 'Picture Height',
            'picture_width' => 'Picture Width',
            'style_id' => 'Style ID',
            'style_name_en' => 'Style Name En',
            'style_name_cn' => 'Style Name Cn',
            'series' => 'Series',
            'tag' => 'Tag',
            'detail' => 'Detail',
            'collect_count' => 'Collect Count',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
