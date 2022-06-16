<?php

function stable_usort(array &$array, callable $compare) {
    $arrayAndPos = [];
    $pos = 0;
    foreach ($array as $value) {
        $arrayAndPos[] = [$value, $pos++];
    }
    usort($arrayAndPos, function($a, $b) use($compare) {
        return $compare($a[0], $b[0]) ?: $a[1] <=> $b[1];
    });
    $array = [];
    foreach ($arrayAndPos as $elem) {
        $array[] = $elem[0];
    }
}