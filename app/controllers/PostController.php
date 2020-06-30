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
     * Show read page for post
     */
    public function readAction($id)
    {
//        $post = Post::findFirst("id={$id}");
//        $this->view->setVar('data', $post->body);
    }

    /**
     * Show read data for post
     */
    public function readDataAction($id)
    {
        $post = Post::findFirst("id={$id}");
        return json_encode($post);
    }

    /**
     * Edits a post
     *
     * @param string $id
     */
    public function editAction($id)
    {

    }

    /**
     * Update a post
     *
     * @param string $id
     */
    public function updateAction($id)
    {
        // TODO:: delete deleted original files. (db and actual) (see file service)
        // TODO:: later, refactoring from duplicated store function to service logic

        $post = Post::findFirst($id);
        $post->title = $this->request->getJsonRawBody()->title;
        $post->body = json_encode($this->request->getJsonRawBody()->blocks);

        $originalFiles = $this->request->getJsonRawBody()->originalFiles;
        $files = $this->request->getJsonRawBody()->files;
        $deletedFiles = array_diff(\App\Plugins\Arr::pluck($originalFiles, 'url'), \App\Plugins\Arr::pluck($files, 'url'));

        $this->fileService->deleteFiles($post->id, $deletedFiles);
        $this->fileService->saveFile($post->id, json_encode($files));

        if( $post->update() ){
            return json_encode([
                'status'    => 'success',
                'id'        => $post->id
            ]);
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
        $post->body = str_replace("&#34;", "\"", urldecode( $this->request->getPost("content", "string") ));
        $post->user_id = $this->session->get("user");

        if (!$post->save()) {
            return json_encode([
                'status'    => 'fail',
                'error'     => 'fail to store'
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
