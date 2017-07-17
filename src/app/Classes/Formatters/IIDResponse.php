<?php
/**
 * Created by PhpStorm.
 * User: mihai
 * Date: 6/20/17
 * Time: 3:04 PM.
 */

namespace LaravelEnso\Risco\app\Classes\Formatters;

use LaravelEnso\Risco\app\Classes\OrderableKV;

class IIDResponse
{
    public static function format($riscoIdentificationResponse)
    {
        if (!$riscoIdentificationResponse) {
            return;
        }

        $result = self::processIdentificationData($riscoIdentificationResponse);

        return $result;
    }

    private static function processIdentificationData($riscoResponse)
    {
        $companyData = $riscoResponse->getRawData()->dateIdentificareFirma;

        $result = [];

        $result[] = new OrderableKV(__('Name'), $companyData->nume, 1);
        $result[] = new OrderableKV(__('Country'), $companyData->tara, 2);
        $result[] = new OrderableKV(__('County'), $companyData->judet, 3);
        $result[] = new OrderableKV(__('City'), $companyData->localitate, 4);
        $result[] = new OrderableKV(__('Address'), $companyData->adresa, 5);
        $result[] = new OrderableKV(__('CUI'), $companyData->codFiscal, 6);
        $result[] = new OrderableKV(__('Commerce Registry Number'), $companyData->nrInregistrareRecom, 7);
        $result[] = new OrderableKV(__('Legal Type'), $companyData->formaLegala, 7);
        $result[] = new OrderableKV(__('Update Date'), $companyData->dataUltimeiActualizari, 7);
        $result[] = new OrderableKV(__('Status'), $companyData->stare, 7);
        $result[] = new OrderableKV(__('VAT'), $companyData->TVAlaIncasare, 7);
        $result[] = new OrderableKV(__('Caen'), $companyData->caen, 7);
        $result[] = new OrderableKV(__('Caen Description'), $companyData->caen_desc, 7);
        $result[] = new OrderableKV(__('Administrator'), $companyData->administrator, 7);
        $result[] = new OrderableKV(__('County'), $companyData->cifra_de_afaceri, 7);
        $result[] = new OrderableKV(__('Net Profit'), $companyData->profit_net, 7);
        $result[] = new OrderableKV(__('Phone #1'), $companyData->telefon, 7);
        $result[] = new OrderableKV(__('Phone #2'), $companyData->telefon2, 7);
        $result[] = new OrderableKV(__('Fax'), $companyData->fax, 7);
        $result[] = new OrderableKV(__('Website'), $companyData->website, 7);

        return $result;
    }
}
