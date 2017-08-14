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
use app\models\Style;

class StyleController extends Controller {

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
            $result = Style::find()->andWhere(['status'=>1])->orderBy('id desc')->limit((int)$size)->offset($start)->all();
            $count = Style::find()->andWhere(['status'=>1])->count('id');
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
            $proxy = new Style();
            $proxy->setAttributes($data, false);
            if($proxy->save()){
                return ['code'=>200];
            }
            return ['code'=>5000,'data'=>$proxy->errors];
        }

        return $this->render('edit',[
            'data' => new Style()
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
            $proxy = Style::find()->andWhere(['id'=>$id])->one();
            $proxy->setAttributes($data);
            if($proxy->save()){
                return ['code'=>200];
            }
            return ['code'=>5000,'data'=>$proxy->errors];
        }
        $id = Yii::$app->request->get('id');
        $data = Style::find()->andWhere(['id'=>$id])->asArray()->one();
        return $this->render('edit',['data' => $data]);
    }

    public function actionDelete(){
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        $proxy = Style::find()->where('id=:id', [':id'=>$id])->one();
        $proxy->status = 0;
        $proxy->save();
        return ['code'=>200];
    }
}
