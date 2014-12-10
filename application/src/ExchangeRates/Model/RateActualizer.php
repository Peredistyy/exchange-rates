<?php

namespace ExchangeRates\Model;

use Doctrine\ORM\EntityManager;
use ExchangeRates\Model\CurrencyProvider;
use ExchangeRates\Model\CurrencyProvider\YahooAdapter;

class RateActualizer
{

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function actualize()
    {
        $currencyProvider = new CurrencyProvider(new YahooAdapter());

        $ratesObjects = $this->getEntityManager()
            ->getRepository('ExchangeRates\Entity\Rate')
            ->findAll();

        foreach ($ratesObjects as $rateObject) {
            $rateObject->setRate(
                $currencyProvider->getRate(
                    $rateObject->getCurrencyOf()->getName(),
                    $rateObject->getCurrencyIn()->getName()
                )
            );
        }

        $this->getEntityManager()->flush();
    }

    protected function getEntityManager()
    {
        return $this->entityManager;
    }
}