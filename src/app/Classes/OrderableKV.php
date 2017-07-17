<?php
/**
 * Created by PhpStorm.
 * User: mihai
 * Date: 7/17/17
 * Time: 2:43 PM
 */

namespace LaravelEnso\Risco\app\Classes;


class OrderableKV
{

    public $key;
    public $value;
    public $order;

    public function __construct($key, $value, $order)
    {
        $this->key = $key;
        $this->value = $value;
        $this->order = $order;
    }
}