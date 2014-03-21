<?php namespace Ipsum\Core\Library\Facades;

use Illuminate\Support\Facades\Facade;

class File extends Facade {

    protected static function getFacadeAccessor()
    {
        return new \Ipsum\Core\Library\Filesystem;
    }

}