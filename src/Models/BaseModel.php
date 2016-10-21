<?php
namespace Ipsum\Core\Models;

use Eloquent;
use Validator;

class BaseModel extends Eloquent {
    
    protected $nullable = [];
    
     /**
     * Listen for save event
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            self::setNullables($model);
        });
    }
    
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

    public static function getRules()
    {
        // Pour être rétrocompatible
        return static::$rules;
    }

    public static function validate($data, $rules = null, $message = array()) {
        $rules = $rules === null ? static::getRules() : $rules;
        return Validator::make($data , $rules, $message);
    }
}
