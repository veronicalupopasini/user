<?php

namespace Esc\User\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\QueryException;
use Esc\User\Entity\User;
use RuntimeException;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements EscUserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param AttributeBag $parameters
     * @return mixed
     * @throws QueryException
     */
    public function findByCriteria(AttributeBag $parameters)
    {
        return $this->createQueryBuilder('user')
            ->select('user.id', 'user.active', 'user.email', 'user.roles', 'user.username')
            ->addCriteria($this->getPaginatedAndFilteredCriteria($parameters))
            ->getQuery()
            ->execute();
    }

    /**
     * @param int $id
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function readOneById(int $id)
    {
        return $this->createQueryBuilder('user')
            ->select('user.id', 'user.active', 'user.email', 'user.roles', 'user.username')
            ->where('user.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findOneById(int $id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function countByCriteria(array $filters): int
    {
        return count($this->matching($this->getFiltersCriteria($filters)));
    }

    public function getFiltersCriteria(array $filters): Criteria
    {
        $criteria = Criteria::create();

        $filtersBag = $this->prepareFiltersCriteria($filters);

        $criteria = $this->getUsernameCriteria($criteria, $filtersBag->get('username', '') ?? '');
        $criteria = $this->getActiveCriteria($criteria, $filtersBag->get('active', '') ?? '');

        return $criteria;
    }

    public function getPaginatedAndFilteredCriteria(AttributeBag $parameters): Criteria
    {
        return $this->getFiltersCriteria($parameters->get('filters'))
            ->orderBy($parameters->get('sortBy'))
            ->setMaxResults($parameters->get('limit'))
            ->setFirstResult($parameters->get('offset'));
    }

    public function getUsernameCriteria(Criteria $criteria, string $username): Criteria
    {
        return $criteria->andWhere(Criteria::expr()->startsWith('username', $username));
    }

    public function getActiveCriteria(Criteria $criteria, string $active): Criteria
    {
        if ($active === 'Y') {
            return $criteria->andWhere(Criteria::expr()->eq('active', true));
        }

        if ($active === 'N') {
            return $criteria->andWhere(Criteria::expr()->eq('active', false));
        }

        return $criteria;
    }

    public function prepareFiltersCriteria(array $filters): AttributeBag
    {
        $filtersBag = new AttributeBag();
        $filtersBag->initialize($filters);

        return $filtersBag;
    }

    /**
     * @param int $id
     * @return User
     * @throws RuntimeException
     */
    public function getOneById(int $id)
    {
        $user = $this->findOneById($id);
        if ($user === null) {
            throw new RuntimeException('User not found');
        }
        return $user;
    }
}
