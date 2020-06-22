<?php

namespace App\Plugins;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Enum;

class Acl extends Memory
{
    private $role;
    private $session;
    private $flashSession;
    private $flash;

    public function __construct($di)
    {
        $this->session = $di->getShared('session');
        $this->flashSession = $di->getShared('flashSession');
        $this->flash = $di->getShared('flash');
        $this->role = $this->session->get('role') ? $this->session->get('role') : 'guest';
        $this->setDefaultAction(Enum::DENY);

        $this->setRoles();
        $this->setComponent();
        $this->setAllows();
    }

    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();

        if( ! $this->isAllowed($this->role, $controllerName, $actionName) && !$this->session->get('role') ){
            if( $this->flashSession->has('error') ){
                $this->flash->error( $this->flashSession->getMessages('error')[0] );
            } else {
                $this->flash->error("Permission denied");
            }

            // $dispatcher->forward([
            //     'controller' => 'auth',
            //     'action'     => 'index'
            // ]);

            return false;
        }
    }

    private function setRoles ()
    {
        $this->addRole('guest');
        $this->addRole('member');
        $this->addRole('admin');
    }

    private function setComponent()
    {
        foreach (scandir(APP_PATH."/controllers") as $controller){
            if( !str_ends_with($controller, 'Controller.php') ) continue;

            $controllerName = str_replace("Controller.php","", $controller);

            $_methods = array_map(function($method){
                if ( str_ends_with($method, 'Action') ){
                    return str_replace('Action','',$method);
                }
            }, get_class_methods("App\\Controllers\\".$controllerName."Controller"));

            $actions = array_filter($_methods, function($method){
                return $method != null;
            });

            $this->addComponent(strtolower($controllerName), $actions);
        }
    }

    private function setAllows()
    {
       $this->allow('*', 'index', '*');
//
//        $this->allow('guest', 'auth', ['index', 'signIn', 'signUp', 'register']);
//
//        $this->allow('member', 'auth', ['info', 'logout']);

//        $this->allow('admin', 'admin', '*');
    }
}