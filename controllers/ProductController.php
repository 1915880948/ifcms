<?php
/**
 * Created by PhpStorm.
 * User: jun
 * Date: 2017/8/11
 * Time: 下午1:43
 */

namespace app\controllers;


use app\models\Product;
use app\models\Series;
use app\models\Style;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class ProductController extends Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout','list','create','edit','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ],
            ]
        ];
    }

    public function actionList(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';
            $start = intval(Yii::$app->request->get('start'));
            $size = intval(Yii::$app->request->get('length',10));
            $result = Product::find()->andWhere(['status'=>1])->orderBy('id desc')->limit((int)$size)->offset($start)->all();
            $count = Product::find()->andWhere(['status'=>1])->count('id');
            return [
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $result
            ];
        }
        return $this->render('list');
    }

    public function actionCreate(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';
            $data = [
                'picture' => Yii::$app->request->post('picture'),
                'picture_height' => Yii::$app->request->post('picture_height'),
                'picture_width' => Yii::$app->request->post('picture_width'),
                'number' => Yii::$app->request->post('number'),
                'style_id' => Yii::$app->request->post('style_id'),
                'series' => '|'.implode('|', Yii::$app->request->post('series')).'|',
                'detail' => Yii::$app->request->post('detail'),
                'tag' => '|'.implode('|', Yii::$app->request->post('tag')).'|',
                'create_time' => date('Y-m-d H:i:s')
            ];
            $style = Style::find()->select('name_cn,name_en')->where(['id'=> $data['style_id']])->one();
            $data['style_name_cn'] = $style->name_cn;
            $data['style_name_en'] = $style->name_en;
            $proxy = new Product();
            $proxy->setAttributes($data, false);
            if($proxy->save()){
                return ['code'=>200];
            }
            return ['code'=>5000,'data'=>$proxy->errors];
        }
        $style = Style::find()->andWhere(['status'=> 1, 'shelf_status'=> 1])->asArray()->all();
        $series = Series::find()->andWhere(['status'=> 1, 'shelf_status'=> 1])->asArray()->all();
        return $this->render('edit',[
            'data' => new Product(),
            'style' => $style,
            'series' => $series
        ]);
    }

    public function actionEdit(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';
            $id = Yii::$app->request->post('id');
            $data = [
                'picture' => Yii::$app->request->post('picture'),
                'picture_height' => Yii::$app->request->post('picture_height'),
                'picture_width' => Yii::$app->request->post('picture_width'),
                'number' => Yii::$app->request->post('number'),
                'style_id' => Yii::$app->request->post('style_id'),
                'series' => '|'.implode('|', Yii::$app->request->post('series')).'|',
                'detail' => Yii::$app->request->post('detail'),
                'tag' => '|'.implode('|', Yii::$app->request->post('tag')).'|',
                'update_time' => date('Y-m-d H:i:s')
            ];
            $style = Style::find()->select('name_cn,name_en')->where(['id'=> $data['style_id']])->one();
            $data['style_name_cn'] = $style->name_cn;
            $data['style_name_en'] = $style->name_en;
            $proxy = Product::find()->andWhere(['id'=>$id])->one();
            $proxy->setAttributes($data);
            if($proxy->save()){
                return ['code'=>200];
            }
            return ['code'=>5000,'data'=>$proxy->errors];
        }
        $id = Yii::$app->request->get('id');
        $data = Product::find()->andWhere(['id'=>$id])->asArray()->one();
        $style = Style::find()->andWhere(['status'=> 1, 'shelf_status'=> 1])->asArray()->all();
        $series = Series::find()->andWhere(['status'=> 1, 'shelf_status'=> 1])->asArray()->all();
        return $this->render('edit',[
            'data' => $data,
            'style' => $style,
            'series' => $series
        ]);
    }

    public function actionDelete(){
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        $proxy = Product::find()->where('id=:id', [':id'=>$id])->one();
        $proxy->status = 0;
        $proxy->save();
        return ['code'=>200];
    }
}