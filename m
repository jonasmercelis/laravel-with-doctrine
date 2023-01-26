#!/usr/bin/env php
<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Console\Kernel as ConsoleKernel;
use App\Infrastructure\Database\DatabaseServiceInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Tools\Console\Command;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\Console\Application;
use Illuminate\Contracts\Foundation\Application as LaravelApplication;

require_once 'vendor/autoload.php';

/** @var $app LaravelApplication */
$app = require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap/app.php';

try {
    /** @var $kernel ConsoleKernel */
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    /** @var $databaseService DatabaseServiceInterface */
    $databaseService = $app->make(DatabaseServiceInterface::class);

    /** @var $em EntityManagerInterface */
    $em = $app->make(EntityManagerInterface::class);
} catch (BindingResolutionException $exception) {

    echo $exception->getMessage();
    exit;
}

try {
    $connection = DriverManager::getConnection($databaseService->getConnectionParameters());
} catch (\Doctrine\DBAL\Exception $exception) {

    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}

// Or use one of the Doctrine\Migrations\Configuration\Configuration\* loaders
$config = new PhpFile('migrations-config.php');

$dependencyFactory = DependencyFactory::fromEntityManager(
    $config,
    new ExistingEntityManager($em)
);

$cli = new Application('Doctrine Migrations');
$cli->setCatchExceptions(true);

$cli->addCommands(array(
    new Command\DumpSchemaCommand($dependencyFactory),
    new Command\ExecuteCommand($dependencyFactory),
    new Command\GenerateCommand($dependencyFactory),
    new Command\LatestCommand($dependencyFactory),
    new Command\ListCommand($dependencyFactory),
    new Command\MigrateCommand($dependencyFactory),
    new Command\RollupCommand($dependencyFactory),
    new Command\StatusCommand($dependencyFactory),
    new Command\SyncMetadataCommand($dependencyFactory),
    new Command\VersionCommand($dependencyFactory),
    new Command\DiffCommand($dependencyFactory)
));

try {
    $cli->run();
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}
