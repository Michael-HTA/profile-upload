<?php

namespace Helpers;

class HTTP{

    //setup the base url
    static $base = "http://localhost/my_project";

    //setting up the redirect function
    static function redirect($path, $query = "",){
        $url = static::$base . $path;

        if($query) $url .="?$query";
        header("location:$url");
        exit();
    }
}