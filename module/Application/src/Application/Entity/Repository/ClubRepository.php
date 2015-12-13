<?php
namespace Application\Entity\Repository;

use Application\Entity\Club;
use Doctrine\ORM\EntityRepository;

class ClubRepository extends EntityRepository
{
    /**
     * @param string $id
     * @return Club
     */
    public function findOneByApiId($id)
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();

        $qb->select('c')
            ->from('Application\Entity\Club', 'c')
            ->where('c.slug = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1);

        $result = $qb->getQuery()->getResult();
        return $result[0];
    }
}
