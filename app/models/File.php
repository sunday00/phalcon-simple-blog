<?php

namespace App\Models;

use App\Plugins\BatchDb;

class File extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $post_id;

    /**
     *
     * @var string
     */
    public $original_name;

    /**
     *
     * @var string
     */
    public $stored_name;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var BatchDb
     */
    public $batchDb;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("phalcon_blog");
        $this->setSource("file");
        $this->belongsTo('post_id', 'App\Models\Post', 'id', ['alias' => 'Post']);

        $this->batchDb = new BatchDb('file');
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return File[]|File|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return File|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function setBatchDb()
    {
        if( !$this->batchDb ) $this->batchDb = new BatchDb('file');
    }

    public function getBatchDb()
    {
        if( !$this->batchDb ) return new BatchDb('file');
        return $this->batchDb;
    }

    public function reset()
    {
        // TODO: Implement reset() method.
    }
}
