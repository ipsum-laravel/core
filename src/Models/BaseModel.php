<?php
namespace Ipsum\Core\Models;

use Eloquent;
use Validator;

class BaseModel extends Eloquent {
    
    protected $nullable = [];
    
    /**
     * Set empty nullable fields to null
     * @param object $model
     */
    protected static function setNullables($model)
    {
        foreach($model->nullable as $field)
        {
            if($model->{$field} === '')
            {
                $model->{$field} = null;
            }
        }
    }    

    public static function validate($data, $rules = null, $message = array()) {
        $rules = $rules === null ? static::getRules() : $rules;
        return Validator::make($data , $rules, $message);
    }
}
