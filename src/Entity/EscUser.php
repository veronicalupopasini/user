<?php

namespace Esc\User\Entity;

interface EscUser
{
    /**
     * @return mixed
     */
    public function getID();

    /**
     * @return mixed
     */
    public function getActive();

    /**
     * @return mixed
     */
    public function setPassword(string $password);

    /**
     * @return mixed
     */
    public function setPlainPassword(string $plainPassword);
}
