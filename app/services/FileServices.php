<?php

namespace App\Services;

use App\Models\File;

class FileServices extends BaseServices
{
    public function saveFile ($post, $files)
    {
        if( json_decode($files) != [] ){
            $file = new File();
            $rows = [];
            foreach ( json_decode($files) as $postedFile ){
                array_push( $rows, [$post, $postedFile->original_name, $postedFile->url, $postedFile->type] );
            }

            return $file->batchDb->setRows(["post_id", "original_name", "stored_name", "type"])->setValues($rows)->insert();
        }
        return;
    }
}