<?php

namespace ExchangeRates\Model\CurrencyProvider;

class YahooAdapter implements InterfaceAdapter
{
    public function getCurrenciesCodeList()
    {
        $result = array();

        $url = "http://finance.yahoo.com/webservice/v1/symbols/allcurrencies/quote?format=json";

        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        foreach ($response->json()["list"]["resources"] as $item) {
            $result[] = str_replace("=X", "", $item["resource"]["fields"]["symbol"]);

        }

        return $result;
    }

    public function getRate($currencyOf, $currencyIn)
    {
        $query = 'select * from yahoo.finance.xchange where pair in ("' . $currencyOf . $currencyIn . '")';

        $url = "http://query.yahooapis.com/v1/public/yql"
            . "?q=" . $query
            . "&env=store://datatables.org/alltableswithkeys"
            . "&format=json";

        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        return $response->json()["query"]["results"]["rate"]["Rate"];
    }
}
