<?php
/**
 * Created by PhpStorm.
 * User: jun
 * Date: 2017/8/11
 * Time: 下午1:40
 */

namespace app\controllers;


use yii\web\Controller;

class StyleController extends Controller {
    public function actionList(){
        return $this->render('list');
    }
}