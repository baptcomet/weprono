<?php
namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findEnabled()
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();

        $qb->select('u')
            ->from('Application\Entity\User', 'u')
            ->where('u.disabled = 0');

        return $qb->getQuery()->getResult();
    }
}
