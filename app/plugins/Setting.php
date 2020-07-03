<?php

namespace App\Plugins;

use Phalcon\Http\Cookie;

class Setting
{
    public static function getTheme ()
    {
        return isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'rustic';
    }
}