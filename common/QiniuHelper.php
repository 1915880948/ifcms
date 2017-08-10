<?php

/**
 * Created by PhpStorm.
 * User: jun
 * Date: 2017/6/21
 * Time: 上午11:11
 */
namespace app\common;
use Qiniu\Auth;
use Yii;
use yii\helpers\Json;

class QiniuHelper {
    public static function getToken(){
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
}