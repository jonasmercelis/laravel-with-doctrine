<?php declare(strict_types=1);

namespace App\Infrastructure\Database;

final readonly class DatabaseService implements DatabaseServiceInterface
{
    private array $connectionParameters;

    public function __construct(
        string  $host,
        string  $username,
        string  $password,
        int     $port,
        string  $database,
        string  $connection = 'pdo_mysql'
    )
    {
        $this->connectionParameters = [
            'driver' => $connection,
            'host' => $host,
            'user' => $username,
            'password' => $password,
            'port' => $port,
            'dbname' => $database
        ];
    }

    public function getConnectionParameters(): array
    {
        return $this->connectionParameters;
    }
}
