<?php
namespace Ipsum\Core\Library;

use DB;
use Cache;
// http://forumsarchive.laravel.io/viewtopic.php?pid=68196


class DBconfiguratorObject implements \ArrayAccess, \Iterator,  \Serializable {
    protected $config = array();
    protected $table = null;
    protected $tableName = null;

    private static $_instance = null;

    public function __construct($tableName = 'config') {
        $this->tableName = $tableName;
        $this->table = DB::table($tableName)->rememberForever($tableName);
        $this->config = $this->table->lists('value', 'key');
    }

    public function serialize(){
        return serialize($this->config);
    }

    public function unserialize($serialized) {
        $config = unserialize($serialized);
        foreach($config as $key => $value){
            $this[$key] = $value;
        }
    }

    public function toJson() {
        return json_encode($this->config);
    }

   /**
    * Notations d’index
    */
    public function offsetGet($key) {
        return $this->config[$key];
    }

    public function offsetSet($key, $value) {
        if($this->offsetExists($key)){
            $result = DB::table($this->tableName)->where('key', $key)->update(array(
                'value' => $value
            ));
        } else {
            DB::table($this->tableName)->insert(array(
                'key' => $key,
                'value' => $value
            ));
        }
        $this->config[$key] = $value;
        Cache::forget($this->tableName);
    }

    public function offsetExists($key) {
        return isset($this->config[$key]);
    }

    public function offsetUnset($key) {
        unset($this->config[$key]);
        $this->table->where('key', $key)->delete();
        Cache::forget($this->tableName);
    }

   /**
    * Itération de l'objet
    */
    public function count()
    {
        return count($this->config);
    }
    public function current()
    {
        return current($this->config);
    }
    public function next()
    {
        next($this->config);
    }
    public function valid()
    {
        return ($this->key() === NULL ? false : true);
    }
    public function key()
    {
        return key($this->config);
    }
    public function rewind()
    {
        reset($this->config);
    }
}