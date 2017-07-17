<?php
/**
 * Created by PhpStorm.
 * User: mihai
 * Date: 6/20/17
 * Time: 3:04 PM.
 */

namespace LaravelEnso\Risco\app\Classes\Formatters;

use LaravelEnso\Risco\app\Classes\DTOs\FinancialData;
use LaravelEnso\Risco\app\Classes\DTOs\FiscalDetails;
use LaravelEnso\Risco\app\Classes\OrderableKV;

class FINResponse
{
    public static function format($riscoFinancialResponse)
    {
        if (!$riscoFinancialResponse) {
            return;
        }

        $result = new FinancialData();

        $result->companyData = self::processCompanyData($riscoFinancialResponse);
        $result->caenData = self::processCaenData($riscoFinancialResponse);
        $result->financialData = self::processFinancialData($riscoFinancialResponse);

        return $result;
    }

    private static function processCompanyData($riscoResponse)
    {
        $companyData = $riscoResponse->getRawData()['CompanyData']['@attributes'];

        $result = [];

        $result[] = new OrderableKV(__('Name'), $companyData['Name'], 1);
        $result[] = new OrderableKV(__('Commerce Registry Number'), $companyData['RegNo'], 2);
        $result[] = new OrderableKV(__('Fiscal Code'), $companyData['FiscalCode'], 3);
        $result[] = new OrderableKV(__('Status'), $companyData['State'], 4);
        $result[] = new OrderableKV(__('Address'), $companyData['Strada'], 5);
        $result[] = new OrderableKV(__('City'), $companyData['Localitate'], 6);
        $result[] = new OrderableKV(__('County'), $companyData['Judet'], 7);

        $result[] = new OrderableKV(__('Legal Type'), $companyData['LegalType'], 8);
        $result[] = new OrderableKV(__('Start Date'), $companyData['DateStart'], 9);
        $result[] = new OrderableKV(__('Update Date'), $companyData['DateUpdate'], 10);

        return $result;
    }

    private static function processCaenData($riscoResponse)
    {
        $caenData = $riscoResponse->getRawData()['CompanyData']['Caen']['@attributes'];

        $result = [];

        $result[] = new OrderableKV(__('Caen Code'), $caenData['Caen'], 1);
        $result[] = new OrderableKV(__('Description'), $caenData['Descriere'], 2);
        $result[] = new OrderableKV(__('Version'), $caenData['Versiune'], 3);

        return $result;
    }

    private static function processFinancialData($riscoResponse)
    {
        $financialData = $riscoResponse->getRawData()['CompanyData']['Financial'];

        $result = [];

        foreach ($financialData as $item) {
            $attributes = $item['@attributes'];

            $tmp = new FiscalDetails($attributes['Luna'], $attributes['An']);
            $tmp->addDetail(__('Active imobilizate - total'), $attributes['F10_0042'], 1);
            $tmp->addDetail(__('Stocuri'), $attributes['F10_0052'], 1);
            $tmp->addDetail(__('Creante'), $attributes['F10_0062'], 1);
            $tmp->addDetail(__('Active circulante - total'), $attributes['F10_0092'], 1);
            $tmp->addDetail(__('Capital'), $attributes['F10_0222'], 1);
            $tmp->addDetail(__('Capitaluri proprii - total'), $attributes['F10_0372'], 1);
            $tmp->addDetail(__('Cifra de afaceri neta'), $attributes['F20_0012'], 1);
            $tmp->addDetail(__('Profit net'), $attributes['F20_0672'], 1);
            $tmp->addDetail(__('Pierdere neta'), $attributes['F20_0682'], 1);
            $tmp->addDetail(__('Casa si conturi la banci'), $attributes['F10_0082'], 1);
            $tmp->addDetail(__('Numar angajati'), $attributes['F30_0232'], 1);
            $tmp->addDetail(__('Venituri in avans'), $attributes['F10_0162'], 1);
            $tmp->addDetail(__('Cheltuieli in Avans'), $attributes['F10_0102'], 1);
            $tmp->addDetail(__('Cheltuieli Totale'), $attributes['F20_0612'], 1);
            $tmp->addDetail(__('Venituri Totale'), $attributes['F20_0602'], 1);
            $tmp->addDetail(__('Profit brut'), $attributes['F20_0632'], 1);
            $tmp->addDetail(__('Pierdere bruta'), $attributes['F20_0622'], 1);

            $result[] = $tmp;
        }

        return $result;
    }
}
