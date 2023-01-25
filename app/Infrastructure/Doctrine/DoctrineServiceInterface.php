<?php declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

interface DoctrineServiceInterface
{
    /**
     * Get the Entity manager.
     */
    public function getEntityManager(): EntityManagerInterface;
    /**
     * Bootstrap the Doctrine Service.
     */
    public function initialize(): void;
}
