<?php

namespace App\Helpers;
use Excel;

class SlugWithParent
{
    public static function getSlugWithParent($file, $column, $columnParent)
    {
        $filePath = storage_path($file);
        $data = Excel::toArray([], $filePath);
        $leaguesSlugWithParent = [];
        
        foreach ($data[0] as $row) {
            $leaguesSlugWithParent[] = [
                'slug' => $row[$column],
                'slugParent' => $row[$columnParent],
            ];
        }
        
        // Eliminar la primera fila si se desea
        array_shift($leaguesSlugWithParent);
        
        return $leaguesSlugWithParent;
    }


}
