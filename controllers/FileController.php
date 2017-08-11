<?php
/**
 * Created by PhpStorm.
 * User: jun
 * Date: 2017/8/11
 * Time: 下午2:24
 */

namespace app\controllers;


use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;

class FileController extends Controller {
    public $enableCsrfValidation = false;
    public function actionToken(){
        Yii::$app->response->format = 'json';
        $qiniu = Yii::$app->params['qiniu'];
        $auth = new Auth($qiniu['accessKey'], $qiniu['secretKey']);
        $policy = [
            "returnBody" => Json::encode([
                'key'=>'$(key)',
                'hash'=>'$(hash)',
                'w'=>'$(imageInfo.width)',
                'h'=>'$(imageInfo.height)',
            ])
        ];
        $token = $auth->uploadToken($qiniu['bucket'], null, 3600, $policy);

        return ['uptoken'=>$token];
    }

    public function actionUpload(){
        Yii::$app->response->format = 'json';
        $qiniu = Yii::$app->params['qiniu'];
        $handle = new UploadManager();
        $auth = new Auth($qiniu['accessKey'], $qiniu['secretKey']);
        $policy = [
            "returnBody" => Json::encode([
                'key'=>'$(key)',
                'hash'=>'$(hash)',
                'w'=>'$(imageInfo.width)',
                'h'=>'$(imageInfo.height)',
            ])
        ];
        $token = $auth->uploadToken($qiniu['bucket'], null, 3600, $policy);

        $file = UploadedFile::getInstanceByName('file');
        $extension = pathinfo($file->name, PATHINFO_EXTENSION);
        $key = uniqid();

        list($ret, $err) = $handle->putFile($token, $key.'.'.$extension, $file->tempName);
        if($err !== null){
            return ['code'=>4000,'data'=>$err];
        } else {
            return ['code'=>200, 'data'=>'http://img-cdn.suixiangpin.com/'.$ret['key'],'info'=>$ret];
        }
    }
}