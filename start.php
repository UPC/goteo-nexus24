<?php

use Goteo\Application\Config;

// Autoload additional Classes
Config::addAutoloadDir(__DIR__ . '/src');

// Adding payment method
\Goteo\Payment\Payment::addMethod('Goteo\Payment\Method\Nexus24PaymentMethod');
