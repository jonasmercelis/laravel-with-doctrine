#!/usr/bin/env php
<?php declare(strict_types=1);

use App\Console\Kernel as ConsoleKernel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;

require_once 'vendor/autoload.php';

/** @var $app Application */
$app = require_once __DIR__ . DIRECTORY_SEPARATOR . '/bootstrap/app.php';

try {
    /** @var $kernel ConsoleKernel */
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    /** @var $entityManager EntityManagerInterface */
    $entityManager = $app->make(EntityManagerInterface::class);

} catch (BindingResolutionException $exception) {

    echo $exception->getMessage();
    exit;
}

try {

    ConsoleRunner::run(
        new SingleManagerProvider($entityManager)
    );

} catch (Exception $exception) {
    echo "Exception!";
    echo $exception->getMessage();
}

