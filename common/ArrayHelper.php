<?php
/**
 * Created by PhpStorm.
 * User: jun
 * Date: 2017/8/11
 * Time: 下午4:13
 */

namespace app\common;


class ArrayHelper {
    public static function stringToArray($string){
        $array = explode('|', trim($string, '|'));
        return $array;
    }
}