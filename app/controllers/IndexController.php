<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Visitor;
use App\Models\Post;
use App\Services\TagServices;

class IndexController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function infoAction ()
    {
        $visitor = new Visitor();
        $post = Post::count();
        //TODO:: tag cloud

        $visitor->ip = $this->request->getClientAddress();
        $visitor->save();

        $cnt = $visitor->count();



        return json_encode([
            'success'   => 1,
            'visit'     => $cnt,
            'post'      => $post,
            'tags'      => TagServices::getMostAppliedTags()
        ]);
    }
    
    public function aboutAction ()
    {

    }

}

