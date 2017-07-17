<?php
/**
 * Created by PhpStorm.
 * User: mihai
 * Date: 6/20/17
 * Time: 3:04 PM.
 */

namespace LaravelEnso\Risco\app\Classes\Formatters;

use LaravelEnso\Risco\app\Classes\OrderableKV;

class STSResponse
{
    public static function format($riscoStatusResponse)
    {
        if (!$riscoStatusResponse) {
            return;
        }

        $result = self::processStatusData($riscoStatusResponse);

        return $result;
    }

    private static function processStatusData($riscoResponse)
    {
        $companyData = $riscoResponse->getRawData()->dateIdentificareFirma;

        $result = [];

        $result[] = new OrderableKV(__('Name'), $companyData->nume, 1);
        $result[] = new OrderableKV(__('CUI'), $companyData->codFiscal, 2);
        $result[] = new OrderableKV(__('VAT'), $companyData->TVAlaIncasare, 3);
        $result[] = new OrderableKV(__('Status'), $companyData->stare, 4);
        $result[] = new OrderableKV(__('Update Date'), $companyData->dataUltimeiActualizari, 5);

        return $result;
    }
}
