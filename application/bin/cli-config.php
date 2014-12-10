<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$app = new \ExchangeRates\App();
return ConsoleRunner::createHelperSet($app->getEntityManager());
