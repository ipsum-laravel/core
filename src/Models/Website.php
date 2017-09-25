<?php
namespace Ipsum\Core\Models;


class Website extends BaseModel {

	protected $table = 'website';

    public $timestamps = false;


    static public function types()
    {
        return self::select('type')->groupBy('type')->get();
    }


    /*
     * Relations
     */

    public function parametres()
    {
        return $this->hasMany('Ipsum\Core\Models\Website', 'type', 'type');
    }
}