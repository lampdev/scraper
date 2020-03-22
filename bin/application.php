<?php
/**
 * Application entrypoint script
 */

require __DIR__ . '../vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new GenerateAdminCommand());

$application->run();