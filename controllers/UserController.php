<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\User;

class UserController extends Controller {
    public function behaviors(){
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
            $result = User::find()->andWhere(['status'=>1])->orderBy('id desc')->limit((int)$size)->offset($start)->all();
            $count = User::find()->andWhere(['status'=>1])->count('id');
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
                'nickname' => Yii::$app->request->post('nickname'),
                'portrait' => Yii::$app->request->post('portrait'),
                'gender' => Yii::$app->request->post('gender'),
                'phone' => Yii::$app->request->post('phone'),
                'email' => Yii::$app->request->post('email'),
                'company' => Yii::$app->request->post('company'),
                'province' => Yii::$app->request->post('province'),
                'city' => Yii::$app->request->post('city'),
                'create_time' => date('Y-m-d H:i:s')
            ];
            $proxy = new User();
            $proxy->setAttributes($data, false);
            if($proxy->save()){
                return ['code'=>200];
            }
            return ['code'=>5000,'data'=>$proxy->errors];
        }
        return $this->render('edit',[
            'data' => new User()
        ]);
    }

    public function actionEdit(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';
            $id = Yii::$app->request->post('id');
            $data = [
                'nickname' => trim(Yii::$app->request->post('nickname')),
                'portrait' => Yii::$app->request->post('portrait'),
                'gender' => Yii::$app->request->post('gender'),
                'phone' => Yii::$app->request->post('phone'),
                'email' => Yii::$app->request->post('email'),
                'company' => trim(Yii::$app->request->post('company')),
                'province' => Yii::$app->request->post('province'),
                'city' => trim(Yii::$app->request->post('city')),
                'create_time' => date('Y-m-d H:i:s')
            ];
            $proxy = User::find()->andWhere(['id'=>$id])->one();
            $proxy->setAttributes($data,false);
            if($proxy->save()){
                return ['code'=>200];
            }
            return ['code'=>5000,'data'=>$proxy->errors];
        }
        $id = Yii::$app->request->get('id');
        $data = User::find()->andWhere(['id'=>$id])->one();
        return $this->render('edit',[
            'data' => $data
        ]);
    }

    public function actionDelete(){
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        $proxy = User::find()->where('id=:id', [':id'=>$id])->one();
        $proxy->status = 0;
        $proxy->save();
        return ['code'=>200];
    }
}
