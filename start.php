<?php

use Goteo\Application\Config;
use Symfony\Component\DependencyInjection\Reference;

// Autoload additional Classes
Config::addAutoloadDir(__DIR__ . '/src');

// Adding payment method
\Goteo\Payment\Payment::addMethod('Goteo\Payment\Method\Nexus24PaymentMethod');

$sc = \Goteo\Application\App::getServiceContainer();
// Project processing
$sc->removeDefinition('console.listener.project');
$sc->register('console.listener.project', 'Goteo\Console\EventListener\Nexus24ProjectListener')
->setArguments(array(new Reference('console_logger')));
// Project watcher processing
$sc->removeDefinition('console.listener.watcher');
$sc->register('console.listener.watcher', 'Goteo\Console\EventListener\Nexus24WatcherListener')
->setArguments(array(new Reference('console_logger')));

