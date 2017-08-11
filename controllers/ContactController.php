<?php
/**
 * Created by PhpStorm.
 * User: jun
 * Date: 2017/8/11
 * Time: 下午6:20
 */

namespace app\controllers;


use app\models\Contact;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;

class ContactController extends Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['list','create','edit','delete'],
                        'allow' => true,
                        'roles' => ['@'],
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
            $result = (new Query())->select('a.id,title,message,a.create_time,nickname,phone')->from('contact a')->leftJoin('user b','a.user_id=b.id')->orderBy('a.id desc')->limit((int)$size)->offset($start)->all();
            $count = Contact::find()->count('id');
            return [
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $result
            ];
        }
        return $this->render('list');
    }

    public function actionDelete(){
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        Contact::findOne($id)->delete();
        return ['code'=>200];
    }
}