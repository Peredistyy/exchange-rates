<?php

namespace ExchangeRates\Command;

use Doctrine\ORM\EntityManager;

interface InterfaceCommand
{
    /**
     * @param EntityManager $entityManager
     * @return mixed
     */
    public function setEntityManager(EntityManager $entityManager);
}
