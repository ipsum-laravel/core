<?php
namespace Ipsum\Core\Models;

use Eloquent;
use Validator;

class BaseModel extends Eloquent {

    public static function validate($data) {
        return Validator::make($data , static::$rules);
    }
}