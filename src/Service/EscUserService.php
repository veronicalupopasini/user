<?php

namespace Esc\User\Service;

interface EscUserService
{
    /**
     * @param AttributeBag $data
     */
    public function createUser(AttributeBag $data): void;

    /**
     * @param int $id
     * @param AttributeBag $data
     */
    public function updateUser(int $id, AttributeBag $data): void;

    /**
     * @param int $id
     */
    public function deleteUser(int $id): void;
}
