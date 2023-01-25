<?php declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Infrastructure\Database\DatabaseServiceInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use Illuminate\Support\Env;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

final class DoctrineService implements DoctrineServiceInterface
{
    private const PROD_ENV_VALUE = 'prod';

    // Entity location directory.
    public const ENTITY_DIRECTORY = 'Entities';

    private readonly DatabaseServiceInterface $databaseService;
    private readonly EntityManagerInterface $entityManager;
    private readonly LoggerInterface $logger;
    private readonly Env $environment;
    private readonly CacheItemPoolInterface $cache;

    public function __construct(
        DatabaseServiceInterface $databaseService,
        Env $environment,
        LoggerInterface $logger,
        CacheItemPoolInterface $cache
    )
    {
        $this->databaseService = $databaseService;
        $this->environment = $environment;
        $this->logger = $logger;
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     * @throws MissingMappingDriverImplementation
     * @throws Exception
     */
    public function initialize(): void
    {
        // Set the path where the Doctrine entities are.
        $entityPaths = [
            dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . self::ENTITY_DIRECTORY
        ];

        $configuration = ORMSetup::createAttributeMetadataConfiguration(
            paths: $entityPaths,
            isDevMode: $this->isDevMode()
        );

        if ($this->isDevMode()) {

            $queryCache = new ArrayAdapter();
            $metadataCache = new ArrayAdapter();
            $configuration->setAutoGenerateProxyClasses(true);
        } else {

            $queryCache = $this->cache;
            $metadataCache = $this->cache;
            $configuration->setAutoGenerateProxyClasses(false);
        }

        $configuration->setQueryCache($queryCache);
        $configuration->setMetadataCache($metadataCache);
        $configuration->setProxyDir(dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'storage/proxies');
        $configuration->setProxyNamespace('DoctrineProxies');

        $connection = DriverManager::getConnection(
            $this->databaseService->getConnectionParameters(),
            $configuration
        );

        // Create the entity manager.
        $this->entityManager = new EntityManager($connection, $configuration);
    }

    /** @inheritDoc */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    private function isDevMode(): bool
    {
        return ((string)$this->environment::get('APP_ENV')) !== self::PROD_ENV_VALUE;
    }
}
