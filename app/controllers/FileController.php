<?php
declare(strict_types=1);

namespace App\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model;
use App\Models\File;

class FileController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Searches for file
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, '\App\Models\File', $_GET)->getParams();
        $parameters['order'] = "id";

        $paginator   = new Model(
            [
                'model'      => '\App\Models\File',
                'parameters' => $parameters,
                'limit'      => 10,
                'page'       => $numberPage,
            ]
        );

        $paginate = $paginator->paginate();

        if (0 === $paginate->getTotalItems()) {
            $this->flash->notice("The search did not find any file");

            $this->dispatcher->forward([
                "controller" => "file",
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
     * Edits a file
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $file = File::findFirstByid($id);
            if (!$file) {
                $this->flash->error("file was not found");

                $this->dispatcher->forward([
                    'controller' => "file",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $file->id;

            $this->tag->setDefault("id", $file->id);
            $this->tag->setDefault("post_id", $file->post_id);
            $this->tag->setDefault("original_name", $file->original_name);
            $this->tag->setDefault("stored_name", $file->stored_name);
            $this->tag->setDefault("type", $file->type);
            
        }
    }

    /**
     * Creates a new file
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "file",
                'action' => 'index'
            ]);

            return;
        }

        $file = new File();
        $file->postId = $this->request->getPost("post_id", "int");
        $file->originalName = $this->request->getPost("original_name", "int");
        $file->storedName = $this->request->getPost("stored_name", "int");
        $file->type = $this->request->getPost("type", "int");
        

        if (!$file->save()) {
            foreach ($file->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "file",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("file was created successfully");

        $this->dispatcher->forward([
            'controller' => "file",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a file edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "file",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $file = File::findFirstByid($id);

        if (!$file) {
            $this->flash->error("file does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "file",
                'action' => 'index'
            ]);

            return;
        }

        $file->postId = $this->request->getPost("post_id", "int");
        $file->originalName = $this->request->getPost("original_name", "int");
        $file->storedName = $this->request->getPost("stored_name", "int");
        $file->type = $this->request->getPost("type", "int");
        

        if (!$file->save()) {

            foreach ($file->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "file",
                'action' => 'edit',
                'params' => [$file->id]
            ]);

            return;
        }

        $this->flash->success("file was updated successfully");

        $this->dispatcher->forward([
            'controller' => "file",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a file
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $file = File::findFirstByid($id);
        if (!$file) {
            $this->flash->error("file was not found");

            $this->dispatcher->forward([
                'controller' => "file",
                'action' => 'index'
            ]);

            return;
        }

        if (!$file->delete()) {

            foreach ($file->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "file",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("file was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "file",
            'action' => "index"
        ]);
    }
}