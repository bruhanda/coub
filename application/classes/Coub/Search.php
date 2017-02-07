<?php

defined('SYSPATH') or die('No direct script access.');

class Coub_Search {

    /**
     * @param $method
     * @return Coub_Method
     */
    public static function method($method) {
        $classname = sprintf('Coub_Search_%s', ucfirst($method));
        return new $classname;
    }

}

// End Coub_Search
