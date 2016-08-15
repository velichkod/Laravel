<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 4/5/2016
 * Time: 12:48 PM
 */

namespace App\Services\Payments\Contracts;


use App\Core\Traits\MagicFieldsTrait;
use App\User;

class Payload
{
    use MagicFieldsTrait;

    public function __construct($attributes = array()){
        $this->_attributes = array_merge($this->_attributes, $attributes);
    }


    public static function Create($data = array()){
        return new Payload($data);
    }

}