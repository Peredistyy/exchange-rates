<?php

namespace ExchangeRates\Component\HttpKernel\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\Translator;

class ControllerResolver extends \Symfony\Component\HttpKernel\Controller\ControllerResolver
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @param \Twig_Environment $twig
     * @return $this
     */
    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;

        return $this;
    }

    /**
     * @return \Twig_Environment
     */
    protected function getTwig()
    {
        return $this->twig;
    }

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
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param Translator $translator
     * @return $this
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * @return Translator
     */
    protected function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param string $class
     * @return \ExchangeRates\Controller\AbstractController
     * @throws \LogicException
     */
    protected function instantiateController($class)
    {
        $twig = $this->getTwig();
        if (!isset($twig)) {
            throw new \LogicException("Twig not injection to ControllerResolver.");
        }

        $entityManager = $this->getEntityManager();
        if (!isset($entityManager)) {
            throw new \LogicException("EntityManager not injection to ControllerResolver.");
        }

        $translator = $this->getTranslator();
        if (!isset($translator)) {
            throw new \LogicException("Translator not injection to ControllerResolver.");
        }

        $controller = new $class($twig, $entityManager);

        $controller->setTwig($twig);
        $controller->setEntityManager($entityManager);
        $controller->setTranslator($translator);

        return $controller;
    }
}
