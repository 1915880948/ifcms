<?php
/**
 * Created by PhpStorm.
 * User: jun
 * Date: 2017/8/11
 * Time: 下午1:40
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Series;

class SeriesController extends Controller {

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

    //关闭csrf验证
    public $enableCsrfValidation = false;

    public function actionList(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';
            $start = intval(Yii::$app->request->get('start'));
            $size = intval(Yii::$app->request->get('length',10));
            $result = Series::find()->andWhere(['status'=>1])->orderBy('sort desc')->limit((int)$size)->offset($start)->all();
            $count = Series::find()->andWhere(['status'=>1])->count('id');
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
                'name_en' => Yii::$app->request->post('name_en'),
                'name_cn' => Yii::$app->request->post('name_cn'),
                'sort' => Yii::$app->request->post('sort'),
                'status' => Yii::$app->request->post('status',1),
                'display_status' => Yii::$app->request->post('display_status'),
                'shelf_status' => Yii::$app->request->post('shelf_status'),
                'create_time' => date('Y-m-d H:i:s')
            ];
            $proxy = new Series();
            $name_cn = Yii::$app->request->post('name_cn');
            $tag = Series::find()->where(['status' => 1,'name_cn' => $name_cn])->all();

            if( $tag ){
                return ['code'=>500];
            }else{
                $proxy->setAttributes($data, false);
                if($proxy->save()){
                    return ['code'=>200];
                }
                return ['code'=>5000,'data'=>$proxy->errors];
            }
        }

        return $this->render('edit',[
            'data' => new Series()
        ]);
    }

    public function actionEdit(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';
            $id = Yii::$app->request->post('id');
            $data = [
                'picture' => Yii::$app->request->post('picture'),
                'name_en' => Yii::$app->request->post('name_en'),
                'name_cn' => Yii::$app->request->post('name_cn'),
                'sort' => Yii::$app->request->post('sort'),
                'status' => Yii::$app->request->post('status',1),
                'display_status' => Yii::$app->request->post('display_status'),
                'shelf_status' => Yii::$app->request->post('shelf_status'),
                'create_time' => date('Y-m-d H:i:s')
            ];
            $proxy = Series::find()->andWhere(['id'=>$id])->one();
            $name_cn = Yii::$app->request->post('name_cn');
            $tag = Series::find()->where(['status' => 1, 'name_cn' => $name_cn])->andWhere(['!=','id',$id])->all();
            if( $tag ){
                return ['code'=>500];
            }else{
                $proxy->setAttributes($data, false);
                if($proxy->save()){
                    return ['code'=>200];
                }
                return ['code'=>5000,'data'=>$proxy->errors];
            }
        }
        $id = Yii::$app->request->get('id');
        $data = Series::find()->andWhere(['id'=>$id])->asArray()->one();
        return $this->render('edit',['data' => $data]);
    }

    public function actionDelete(){
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        $proxy = Series::find()->where('id=:id', [':id'=>$id])->one();
        $proxy->status = 0;
        $proxy->save();
        return ['code'=>200];
    }
}
