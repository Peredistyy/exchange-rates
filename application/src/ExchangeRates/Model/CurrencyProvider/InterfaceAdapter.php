<?php

namespace ExchangeRates\Model\CurrencyProvider;

interface InterfaceAdapter
{
    public function getCurrenciesCodeList();
    public function getRate($currencyOf, $currencyIn);
}
