<?php

namespace ExchangeRates\Command;

use ExchangeRates\Entity\Currency;
use ExchangeRates\Model\CurrencyProvider;
use ExchangeRates\Model\CurrencyProvider\YahooAdapter;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CurrencyLoad extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('load')->setDescription('Loading all currencies');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $currencyProvider = new CurrencyProvider(new YahooAdapter());

        foreach ($currencyProvider->getCurrenciesCodeList() as $currencyName) {
            if ($currencyName &&
                !$this->getEntityManager()
                    ->getRepository('ExchangeRates\Entity\Currency')
                    ->findOneByName($currencyName)
            ) {
                $currency = new Currency();
                $currency->setName($currencyName);
                $this->getEntityManager()->persist($currency);
            }
        }

        $this->getEntityManager()->flush();
    }
}
