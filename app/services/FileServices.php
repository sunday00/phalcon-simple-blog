<?php

namespace App\Services;

use App\Models\File;

class FileServices extends BaseServices
{
    public function saveFile ($postId, $files)
    {
        if( json_decode($files) != [] ){
            $file = new File();
            $file->setBatchDb();
            $rows = [];
            foreach ( json_decode($files) as $postedFile ){
                array_push( $rows, [$postId, $postedFile->original_name, $postedFile->url, $postedFile->type] );
            }

            return $file->batchDb->setRows(["post_id", "original_name", "stored_name", "type"])->setValues($rows)->insert();
        }
        return false;
    }

    public function deleteFiles($postId, $deletedFiles)
    {
        $files = File::find("post_id = {$postId}");
        foreach ($deletedFiles as $file){
            if( is_file(BASE_PATH."/public/".$file) ){
                unlink(BASE_PATH."/public/".$file);
            }
        }
        $files->delete();
    }
}