<?php
namespace Ipsum\Core\Models;

use Eloquent;
use Validator;

class BaseModel extends Eloquent {

    public static function validate($data, $message = array()) {
        return Validator::make($data , static::$rules, $message);
    }
}