<?php
declare(strict_types=1);

namespace App\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Tag;
use App\Plugins\Setting;

class ControllerBase extends Controller
{
    public function initialize()
    {
        $theme = Setting::getTheme();
        $this->view->setVar('theme', $theme);

        Tag::prependTitle('Phalog | ');
    }

//    public function onConstruct()
//    {
//
//    }

    protected function checkCsrf()
    {
        return $this->security->checkToken();
    }

    protected function redirectBack($msg = 'invalid csrf token')
    {
        $this->flashSession->error($msg);
        return $this->response->redirect($_SERVER['HTTP_REFERER'])->removeHeader('HTTP/1.1 302 Found')->setHeader('HTTP/1.1 303 See Other',null)->setHeader('Status', '303 See Other');
    }
}
