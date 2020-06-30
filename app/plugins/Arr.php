<?php

namespace App\Plugins;

use Phalcon\Helper\Arr as Helper;

class Arr extends Helper
{
    public static function objectArrToArrArr (array $objects)
    {
        $arr = [];
        foreach ($objects as $object){
            array_push($arr, (array) $object);
        }
        return $arr;
    }
}