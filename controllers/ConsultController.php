<?php
/**
 * Created by PhpStorm.
 * User: jun
 * Date: 2017/8/11
 * Time: 下午6:51
 */

namespace app\controllers;


use app\models\Consult;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;

class ConsultController extends Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['list','answer','edit','delete'],
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
            $search = Yii::$app->request->get('search')['value'];
            $result =(new Query())->select('a.id,a.question,a.answer,a.create_time,b.nickname,b.phone,c.number')->from('consult a')->leftJoin('user b','a.user_id=b.id')->leftJoin('product c','a.product_id=c.id');
            $count = Consult::find();
            if($search){
                $result = $result->andWhere(['like', 'question', "%{$search['value']}%"]);
                $count = $count->andWhere(['like', 'question', "%{$search['value']}%"]);
            }
            $result = $result->orderBy('a.id desc')->limit((int)$size)->offset($start)->all();
            $count = $count->count('id');
            return [
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $result
            ];
        }
        return $this->render('list');
    }

    public function actionAnswer(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';
            $id = Yii::$app->request->post('id');
            $content = Yii::$app->request->post('content');
            $proxy = Consult::find()->andWhere(['id'=> $id])->one();
            $proxy->answer = $content;
            if($proxy->save()){
                return ['code'=>200];
            }
            return ['code'=>5000,'data'=>$proxy->errors];
        }
    }

    public function actionDelete(){
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        Consult::findOne($id)->delete();
        return ['code'=>200];
    }
}