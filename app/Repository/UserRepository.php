<?php declare(strict_types=1);

namespace App\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getAllAsCollection(): Collection
    {
        return new ArrayCollection();
    }
}
