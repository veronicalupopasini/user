<?php

namespace Esc\User\Repository;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Zend\Code\Reflection\Exception\RuntimeException;

interface EscUserRepository
{
    /**
     * @param AttributeBag $parameters
     * @return mixed
     */
    public function findByCriteria(AttributeBag $parameters);

    /**
     * @param array $filters
     * @return int
     */
    public function countByCriteria(array $filters): int;

    /**
     * @param int $id
     * @return mixed
     */
    public function readOneById(int $id);

    /**
     * @param AttributeBag $parameters
     * @return Criteria
     */
    public function getPaginatedAndFilteredCriteria(AttributeBag $parameters): Criteria;

    /***
     * @param array $filters
     * @return Criteria
     */
    public function getFiltersCriteria(array $filters): Criteria;

    /**
     * @param Criteria $criteria
     * @param string $username
     * @return Criteria
     */
    public function getUsernameCriteria(Criteria $criteria, string $username): Criteria;

    /**
     * @param Criteria $criteria
     * @param string $active
     * @return Criteria
     */
    public function getActiveCriteria(Criteria $criteria, string $active): Criteria;

    /**
     * @param array $filters
     * @return AttributeBag
     */
    public function prepareFiltersCriteria(array $filters): AttributeBag;

    /**
     * @param int $id
     * @return mixed
     */
    public function findOneById(int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function getOneById(int $id);

}
