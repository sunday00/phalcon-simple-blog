<?php
declare(strict_types=1);

namespace App\Controllers;

use Phalcon\Mvc\Controller;
use App\Plugins\Setting;

class ControllerBase extends Controller
{
    public function initialize()
    {
        $theme = Setting::getTheme();
        $this->view->setVar('theme', $theme);
    }
}
