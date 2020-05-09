<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;

$config = new Configuration();
$config->setProxyDir('Proxies');
$config->setProxyNamespace('App\Proxies');

$paths = [
    'App\\' => realpath(__DIR__) . "/../src/",
];

// Tells Doctrine what mode we want
$isDevMode = true;

// Doctrine connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'password',
    'dbname'   => 'walmart',
    'host'     => 'db'
);

$driverImpl = $config->newDefaultAnnotationDriver($paths, false);
$config->setMetadataDriverImpl($driverImpl);
$entityManager = EntityManager::create($dbParams, $config);

return ConsoleRunner::createHelperSet($entityManager);
