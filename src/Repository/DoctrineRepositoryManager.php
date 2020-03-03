<?php


namespace Esc\User\Repository;


use Doctrine\Common\Collections\Criteria;
use Esc\Repository\CriteriaSearchableRepository;
use Esc\Repository\IdentityRepository;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class DoctrineRepositoryManager extends IdentityRepository implements CriteriaSearchableRepository
{

    /**
     * @inheritDoc
     */
    public function findByCriteria(AttributeBag $parameters)
    {
        // TODO: Implement findByCriteria() method.
    }

    /**
     * @inheritDoc
     */
    public function countByCriteria(array $filters): int
    {
        // TODO: Implement countByCriteria() method.
    }

    /**
     * @inheritDoc
     */
    public function readOneById(int $id)
    {
        // TODO: Implement readOneById() method.
    }

    /**
     * @inheritDoc
     */
    public function getPaginatedAndFilteredCriteria(AttributeBag $parameters): Criteria
    {
        // TODO: Implement getPaginatedAndFilteredCriteria() method.
    }

    /**
     * @inheritDoc
     */
    public function getFiltersCriteria(array $filters): Criteria
    {
        // TODO: Implement getFiltersCriteria() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsernameCriteria(Criteria $criteria, string $username): Criteria
    {
        // TODO: Implement getUsernameCriteria() method.
    }

    /**
     * @inheritDoc
     */
    public function getActiveCriteria(Criteria $criteria, string $active): Criteria
    {
        // TODO: Implement getActiveCriteria() method.
    }

    /**
     * @inheritDoc
     */
    public function prepareFiltersCriteria(array $filters): AttributeBag
    {
        // TODO: Implement prepareFiltersCriteria() method.
    }
}