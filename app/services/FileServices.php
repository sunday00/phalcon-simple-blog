<?php

namespace App\Services;

use App\Models\File;

class FileServices extends BaseServices
{
    public function saveFile ($postId, $files)
    {
        if( json_decode($files) != [] ){
            $file = new File();
            $rows = [];
            foreach ( json_decode($files) as $postedFile ){
                array_push( $rows, [$postId, $postedFile->original_name, $postedFile->url, $postedFile->type] );
            }

            return $file->batchDb->setRows(["post_id", "original_name", "stored_name", "type"])->setValues($rows)->insert();
        }
        return;
    }

    public function deleteFiles($postId, $deletedFiles)
    {
        //TODO:: delete files table by postId (all)
        //TODO:: delete  actual file by file name via arg $deletedFiles
    }
}