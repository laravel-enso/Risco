<?php
/**
 * Created by PhpStorm.
 * User: mihai
 * Date: 7/17/17
 * Time: 3:04 PM.
 */

namespace LaravelEnso\Risco\app\Classes\DTOs;

use LaravelEnso\Risco\app\Classes\OrderableKV;

class FiscalDetails
{
    public $month = null;
    public $year = null;
    public $details = [];

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function addDetail($key, $value, $order)
    {
        $this->details[] = new OrderableKV($key, $value, $order);
    }
}
