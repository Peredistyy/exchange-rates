<?php

namespace ExchangeRates\Model;

use ExchangeRates\Model\CurrencyProvider\InterfaceAdapter;

class CurrencyProvider implements InterfaceAdapter
{

    private $adapter;

    public function __construct(InterfaceAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getCurrenciesCodeList()
    {
        return $this->adapter->getCurrenciesCodeList();
    }

    public function getRate($currencyOf, $currencyIn)
    {
        return $this->adapter->getRate($currencyOf, $currencyIn);
    }
}
