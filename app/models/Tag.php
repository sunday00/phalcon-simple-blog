<?php

namespace App\Models;

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Tag extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("phalcon_blog");
        $this->setSource("tag");
        $this->hasManyToMany('id', 'App\Models\PostTag', 'tag_id', 'post_id', 'App\Models\Post', 'id', ['alias' => 'posts']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tag[]|Tag|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tag|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function getMostAppliedTags ()
    {
        $sql = "SELECT t.title, COUNT(tag_id) as cnt FROM post_tag pt
            LEFT JOIN tag t
            ON pt.tag_id = t.id
            GROUP BY pt.tag_id ORDER BY cnt DESC";
        $model = new Tag();

        return new Resultset(
            null,
            $model,
            $model->getReadConnection()->query($sql)
        );

    }

    public function reset()
    {
        // TODO: Implement reset() method.
    }
}
