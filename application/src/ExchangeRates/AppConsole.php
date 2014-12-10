<?php

namespace ExchangeRates;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use ExchangeRates\Command\InterfaceCommand;

class AppConsole extends Application
{

    /**
     * @var App
     */
    private $app;

    /**
     * @param string $name
     * @param string $version
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);

        $this->app = new App();
    }

    /**
     * @param Command $command
     * @return Command
     */
    public function add(Command $command)
    {
        if ($command instanceof InterfaceCommand) {
            $command->setEntityManager($this->app->getEntityManager());
        }

        return parent::add($command);
    }
}
