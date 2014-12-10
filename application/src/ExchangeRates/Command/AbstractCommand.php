<?php

namespace ExchangeRates\Command;

use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManager;

abstract class AbstractCommand extends Command implements InterfaceCommand
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }
}
