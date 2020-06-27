<?php
declare(strict_types=1);

namespace App\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model;
use App\Models\Post;

class PostController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Searches for post
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, '\App\Models\Post', $_GET)->getParams();
        $parameters['order'] = "id";

        $paginator   = new Model(
            [
                'model'      => '\App\Models\Post',
                'parameters' => $parameters,
                'limit'      => 10,
                'page'       => $numberPage,
            ]
        );

        $paginate = $paginator->paginate();

        if (0 === $paginate->getTotalItems()) {
            $this->flash->notice("The search did not find any post");

            $this->dispatcher->forward([
                "controller" => "post",
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
     * Edits a post
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $post = Post::findFirstByid($id);
            if (!$post) {
                $this->flash->error("post was not found");

                $this->dispatcher->forward([
                    'controller' => "post",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $post->id;

            $this->tag->setDefault("id", $post->id);
            $this->tag->setDefault("user_id", $post->user_id);
            $this->tag->setDefault("title", $post->title);
            $this->tag->setDefault("body", $post->body);
            $this->tag->setDefault("active", $post->active);
            $this->tag->setDefault("created_at", $post->created_at);
            $this->tag->setDefault("updated_at", $post->updated_at);
            
        }
    }

    /**
     * Creates a new post
     */
    public function createAction()
    {

    }

    /**
     * Saves a post edited
     *
     */
    public function storeAction()
    {
        if ( !$this->request->isPost() || !$this->request->getPost('title') ) {
            return json_encode([
                'status'    => 'fail',
                'error'     => 'Not allowed method or No title'
            ]);
        }

        $post = new Post();
        $post->title = $this->request->getPost("title", "string");
        $post->body = str_replace( "&#34;", "\"", $this->request->getPost("content", "string") );
        $post->user_id = $this->session->get("user");

        if (!$post->save()) {
            return json_encode([
                'status'    => 'fail',
                'error'     => 'pail to store'
            ]);
        }

        $this->fileService->saveFile($post->id, str_replace( "&#34;", "\"", $this->request->getPost("files", "string") ));

        return json_encode([
            'status'    => 'success',
            'id'        => $post->id
        ]);
    }

    /**
     * Deletes a post
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $post = Post::findFirstByid($id);
        if (!$post) {
            $this->flash->error("post was not found");

            $this->dispatcher->forward([
                'controller' => "post",
                'action' => 'index'
            ]);

            return;
        }

        if (!$post->delete()) {

            foreach ($post->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "post",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("post was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "post",
            'action' => "index"
        ]);
    }
}
