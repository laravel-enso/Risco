<?php
/**
 * Created by PhpStorm.
 * User: mihai
 * Date: 6/20/17
 * Time: 3:04 PM.
 */

namespace LaravelEnso\Risco\app\Classes;

use LaravelEnso\Helpers\Classes\Object;
use LaravelEnso\Risco\app\Enums\DataTypesEnum;

class PreferencesStructureBuilder
{
    const REFRESH_INTERVAL = 1; //minutes

    public static function build($type)
    {
        $prefObject = new Object();

        self::setDataTypeKeys($prefObject);
        self::setRefreshInterval($prefObject);

        return json_encode($prefObject);
    }

    private static function setDataTypeKeys($prefObject)
    {
        $dataTypeKeys = (new DataTypesEnum())->getKeys();
        $prefObject->dataTypes = $dataTypeKeys;
    }

    private static function setRefreshInterval($prefObject)
    {
        $prefObject->refreshInterval = self::REFRESH_INTERVAL;
    }
}
