<?php
namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class UtilisateurRepository extends EntityRepository
{
    public function findEnabled()
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();

        $qb->select('u')
            ->from('Application\Entity\Utilisateur', 'u')
            ->where('u.disabled = 0');

        return $qb->getQuery()->getResult();
    }
}
