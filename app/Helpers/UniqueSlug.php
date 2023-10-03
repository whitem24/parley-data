<?php

namespace App\Helpers;
use Excel;

class UniqueSlug
{
    public static function getUniqueSlug($file, $column)
    {
        $filePath = storage_path($file);
        $data = Excel::toArray([], $filePath);
        $SportsSlug = [];
        foreach ($data[0] as $row) {
            $SportsSlug[] = $row[$column];
        }
        $uniqueSportsSlug = array_unique($SportsSlug);
        $uniqueSportsSlug = array_values($uniqueSportsSlug);
        array_shift($uniqueSportsSlug);
        
        return $uniqueSportsSlug;
    }

}
