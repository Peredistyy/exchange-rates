<?php

namespace ExchangeRates\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ExchangeRates\Model\RateActualizer;

class CurrencyRateActualize extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('rate-actualize')->setDescription('All rates actualize');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rateActualizer = new RateActualizer($this->getEntityManager());
        $rateActualizer->actualize();
    }
}
