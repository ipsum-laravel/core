<?php
namespace Ipsum\Core\Library;

use Input;

abstract class Validator {

    protected $data;

    public function __construct($data = NULL)
    {
        $this->data = $data ?: Input::all();
    }

    public function validate()
    {
        return \Validator::make($this->data, $this->getRules());
    }

}