<?php

use dump_r\Core;
if (!function_exists('dump_r')) {
    function dump_r($raw, $depth = 1000, $expand = 1, $ret = false) {
        return Core::dump_r($raw, $depth, $expand, $ret);
    }
}