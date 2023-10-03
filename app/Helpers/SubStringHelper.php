<?php

namespace App\Helpers;

class SubStringHelper
{
    public static function insertSportSlug($string, $slug)
    {
        $pos = strpos($string, '/', 5);
        $end = strpos($string, '?', $pos);

        if ($pos !== false && $end !== false) {
            $part1 = substr($string, 0, $pos + 1); // From begin to the fifth /
            $part2 = substr($string, $end); // After the first ?

            
            $result = $part1 . $slug . $part2;

            return $result;
        }

        return null;
    }

}
