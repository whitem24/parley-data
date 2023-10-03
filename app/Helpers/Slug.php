<?php

namespace App\Helpers;
use Excel;

class Slug
{
    public static function getSlug($file,$column)
    {
        $filePath = storage_path($file);
        $data = Excel::toArray([], $filePath);
        $leaguesSlug = [];
        foreach ($data[0] as $row) {
            $leaguesSlug[] = $row[$column];
        }
        array_shift($leaguesSlug);

        
        return $leaguesSlug;
    }

}
