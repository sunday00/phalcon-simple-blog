<?php
declare(strict_types=1);

namespace App\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model;

use App\Models\Post;
use App\Services\TagServices;

use Phalcon\Helper\Arr;

class PostController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, '\App\Models\Post', $_GET)->getParams();
        $parameters['order'] = "id desc";

        $tagQuery = ($this->request->getQuery('tag', 'string', null));
        if($tagQuery){
            $posts = \App\Models\Tag::findFirst("title='{$tagQuery}'")->posts->toArray();
            $parameters[] = "id in (".implode("," , Arr::pluck( (array) $posts, 'id' )).")" ;
        }

        $paginator = new Model(
            [
                'model'      => '\App\Models\Post',
                'parameters' => $parameters,
                'limit'      => 3,
                'page'       => $numberPage,
            ]
        );

        $paginate = $paginator->paginate();

        if (0 === $paginate->getTotalItems() && $this->session->get('role')) {
            $this->flashSession->error("The search did not find any post");
            $this->response->redirect("/post/create");
        } elseif ( 0 === $paginate->getTotalItems() ) {
            $this->flashSession->error("The search did not find any post");
            $this->response->redirect("/");
        }

        $convertedItems = [];
        foreach ($paginate->getItems() as $post){
            $convertedItem = Arr::toObject($post);
            $convertedBody = '';
            foreach( Arr::pluck(Arr::pluck(json_decode($convertedItem->body), 'data'), 'text') as $str){
                $convertedBody.=$str;
            }
            $convertedItem->convertedBody = mb_strimwidth($convertedBody, 0, 160, '...');
            array_push($convertedItems, $convertedItem);
        }

        $paginate->convertedItems = $convertedItems;

        $this->view->page = $paginate;

        if($tagQuery){
            $tagParam = "&tag={$tagQuery}";
            $this->view->setVar('tagParam', $tagParam);
            $this->view->tagParam = $tagParam;
        } else {
            $this->view->setVar('tagParam', '');
            $this->view->tagParam = '';
        }
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
                'limit'      => 3,
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
        $post = (object) array_merge((array) $post, ['tags' => $post->tags->toArray()]);

        return json_encode( $post );
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
        // TODO:: later, refactoring from duplicated store function to service logic

        $post = Post::findFirst($id);
        $post->title = $this->request->getJsonRawBody()->title;
        $post->body = json_encode($this->request->getJsonRawBody()->blocks);

        $post->tags = TagServices::updateTag($id, $this->request);

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
        $tags = TagServices::storeTag($this->request);

        if($tags['newTags']){
            $post->tags = $tags['newTags'];
        }

        if (!$post->save()) {
            return json_encode([
                'status'    => 'fail',
                'error'     => 'fail to store'
            ]);
        }

        if($tags['existedTags']){
            TagServices::savePersistTags( $post->id, $tags['existedTags'] );
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

    /**
     * Deletes a post via api
     *
     * @param string $id
     */
    public function deleteDataAction($id)
    {
        $post = Post::findFirstByid($id);
        if (!$post) {
            $this->flash->error("post was not found");
            return json_encode([
                'status'    => 'fail',
                'error'     => 'already not exists'
            ]);
        }

        foreach( $post->getFile() as $file){
            unlink(BASE_PATH."/public".$file->stored_name);
        }

        TagServices::deleteForeign($id);

        if (!$post->delete()) {
            return json_encode([
                'status'    => 'fail',
                'error'     => 'something is wrong'
            ]);
        }

        return json_encode([
            'status'    => 'success'
        ]);
    }
}
