<?php
declare(strict_types=1);

namespace App\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model;
use App\Models\User;

class UserController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Searches for user
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, '\App\Models\User', $_GET)->getParams();
        $parameters['order'] = "id";

        $paginator   = new Model(
            [
                'model'      => '\App\Models\User',
                'parameters' => $parameters,
                'limit'      => 10,
                'page'       => $numberPage,
            ]
        );

        $paginate = $paginator->paginate();

        if (0 === $paginate->getTotalItems()) {
            $this->flash->notice("The search did not find any user");

            $this->dispatcher->forward([
                "controller" => "user",
                "action" => "index"
            ]);

            return;
        }

        $this->view->page = $paginate;
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        //
    }

    /**
     * Edits a user
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $user = User::findFirstByid($id);
            if (!$user) {
                $this->flash->error("user was not found");

                $this->dispatcher->forward([
                    'controller' => "user",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $user->id;

            $this->tag->setDefault("id", $user->id);
            $this->tag->setDefault("email", $user->email);
            $this->tag->setDefault("password", $user->password);
            $this->tag->setDefault("role_id", $user->role_id);
            $this->tag->setDefault("created_at", $user->created_at);
            $this->tag->setDefault("updated_at", $user->updated_at);
            
        }
    }

    /**
     * Creates a new user
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $user = new User();
        $user->email = $this->request->getPost("email", "int");
        $user->password = $this->request->getPost("password", "int");
        $user->roleId = $this->request->getPost("role_id", "int");
        $user->createdAt = $this->request->getPost("created_at", "int");
        $user->updatedAt = $this->request->getPost("updated_at", "int");
        

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("user was created successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a user edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $user = User::findFirstByid($id);

        if (!$user) {
            $this->flash->error("user does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $user->email = $this->request->getPost("email", "int");
        $user->password = $this->request->getPost("password", "int");
        $user->roleId = $this->request->getPost("role_id", "int");
        $user->createdAt = $this->request->getPost("created_at", "int");
        $user->updatedAt = $this->request->getPost("updated_at", "int");
        

        if (!$user->save()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'edit',
                'params' => [$user->id]
            ]);

            return;
        }

        $this->flash->success("user was updated successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user = User::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user was not found");

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("user was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => "index"
        ]);
    }

    public function signAction ()
    {

    }

    public  function doSignAction()
    {
        $this->view->disable();

        $email      = $this->request->getPost('email', 'string');
        $password   = $this->request->getPost('password', 'string');

        $user = User::findFirst( "email = '{$email}'" );

        if( $this->security->checkHash($this->request->getPost('password'), $user->password) ){
            $this->session->set('role', 'admin');
            return $this->response->redirect("/shielded/dashboard");
        }

        $this->flashSession->error('not permitted');
        return $this->response->redirect("/user/sign");

    }
}
