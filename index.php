<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new \Example\Command\MainCommand());
$application->add(new \Example\Command\SubProcessCommand());
$application->run();