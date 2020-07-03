<?php

namespace App\Controllers;

use App\Plugins\Setting;

class AdminController extends ControllerBase
{
    public function dashboardAction($params = null)
    {
//        dd($params);
    }

    public function getThemeAction()
    {
        return json_encode([
            'success' => 1,
            'theme' => Setting::getTheme()
        ]);
    }
}