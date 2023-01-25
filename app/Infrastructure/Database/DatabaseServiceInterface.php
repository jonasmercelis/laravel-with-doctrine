<?php declare(strict_types=1);

namespace App\Infrastructure\Database;

interface DatabaseServiceInterface
{
    /** Return the database connection array. */
    public function getConnectionParameters(): array;
}
