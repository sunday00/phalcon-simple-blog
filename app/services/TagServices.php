<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\PostTag;
use App\Plugins\BatchDb;
use Phalcon\Helper\Arr;

class TagServices extends BaseServices
{
    public static function updateTag($id, $request)
    {
        PostTag::find("post_id={$id}")->delete();

        if( $request->getJsonRawBody()->tags ){
            $existed = static::getExistedTags( $request->getJsonRawBody()->tags );
            $existedNames = Arr::pluck($existed, 'title');

            $array_tags = array_diff( $request->getJsonRawBody()->tags, $existedNames );

            $tags = [];
            foreach ( $array_tags as $tag ){
                $tagModel = new Tag();
                $tagModel->title = $tag;
                $tags[] = $tagModel;
            }

            static::savePersistTags($id, $existed);
            return $tags;
        }
    }

    public static function storeTag($request)
    {
        if( $request->getPost('tags') ){
            $postedTags = json_decode($request->getPost('tags'));
            $existed = static::getExistedTags( $postedTags );

            $array_tags = [];
            if( $existed ){
                $existedNames = Arr::pluck($existed, 'title');
                $array_tags = array_diff( $postedTags, $existedNames );
            } else {
                $array_tags = $postedTags;
            }

            $tags = [];
            if( $array_tags ){
                foreach ( $array_tags as $tag ){
                    $tagModel = new Tag();
                    $tagModel->title = $tag;
                    $tags[] = $tagModel;
                }
            }

            return ['newTags' => $tags, 'existedTags' => $existed];
        }
    }

    public static function getExistedTags($postedTags)
    {
        $candidateTags = [];

        if( !$postedTags ) return null;

        foreach ( $postedTags as $tag ){
            $candidateTags[] = "'{$tag}'";
        }
        $candidateTags = implode(",", $candidateTags);

        $existed = Tag::query();
        $existed->where("title IN ({$candidateTags})");
        return $existed->execute()->toArray();
    }

    public static function savePersistTags($id, $existed)
    {
        $renewPersist = [];
        foreach ($existed as $exist){
            array_push( $renewPersist, [ $exist['id'], $id ] );
        }

        $batch = new BatchDb('post_tag');
        $batch->setRows(["tag_id", "post_id"])->setValues($renewPersist)->insert();
    }

    public static function deleteForeign($id)
    {
        PostTag::find("post_id={$id}")->delete();
    }

    public static function getMostAppliedTags()
    {
        return Arr::pluck(Tag::getMostAppliedTags()->toArray(), 'title');
    }
}