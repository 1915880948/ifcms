<?php
/**
 * Created by PhpStorm.
 * User: jun
 * Date: 2017/8/10
 * Time: 下午3:16
 */

namespace app\controllers;


use app\models\Admin;
use app\models\IUser;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class AdminController extends Controller {
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

    public function actionLogin(){
        Yii::$app->response->format = 'json';
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $admin = IUser::findByUsername($username);
        if($admin && $admin->password === md5($password)){
            Yii::$app->user->login($admin, 86400);
            return ['code'=>200];
        }
        return ['code'=>500];
    }

    public function actionLogout(){
        if(Yii::$app->user->logout()){
            return $this->redirect(['site/login']);
        }
    }

    public function actionList(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';
            $start = intval(Yii::$app->request->get('start'));
            $size = intval(Yii::$app->request->get('length',10));
            $result = Admin::find()->andWhere(['status'=>1])->orderBy('id desc')->limit((int)$size)->offset($start)->all();
            $count = Admin::find()->andWhere(['status'=>1])->count('id');
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
                'username' => Yii::$app->request->post('username'),
                'password' => md5(Yii::$app->request->post('password')),
                'create_time' => date('Y-m-d H:i:s')
            ];
            $proxy = new Admin();
            if( $data['username'] !=  htmlspecialchars( $data['username'] ) ){
                return ['code'=>500];
            }
            $proxy->setAttributes($data, false);
            if($proxy->save()){
                return ['code'=>200];
            }
            return ['code'=>5000,'data'=>$proxy->errors];
        }
        return $this->render('edit',[
            'data' => new Admin()
        ]);
    }

    public function actionEdit(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';
            $id = Yii::$app->request->post('id');
            $data = [
                'username' => Yii::$app->request->post('username'),
                'password' => Yii::$app->request->post('password')
            ];
            $proxy = Admin::find()->andWhere(['id'=>$id])->one();
            $proxy->setAttribute('username', $data['username']);
            if($proxy->password != $data['password']){
                $proxy->setAttribute('password', md5($data['password']));
            }
            if( $data['username'] !=  htmlspecialchars( $data['username'] ) ){
                return ['code'=>500];
            }

            if($proxy->save()){
                return ['code'=>200,'str'=>htmlspecialchars( $data['username'] ) ];
            }
            return ['code'=>5000,'data'=>$proxy->errors];
        }
        $id = Yii::$app->request->get('id');
        $data = Admin::find()->andWhere(['id'=>$id])->one();
        return $this->render('edit',[
            'data' => $data
        ]);
    }

    public function actionDelete(){
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        $proxy = Admin::find()->where('id=:id', [':id'=>$id])->one();
        $proxy->status = 0;
        $proxy->save();
        return ['code'=>200];
    }
}