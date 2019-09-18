<?php

namespace Esc\User\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Esc\User\Entity\User;

final class UserFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $userEntity = new User();
        $userEntity->setUsername('admin');
        $userEntity->setPlainPassword('admin');
        $userEntity->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $userEntity->setEmail('admin@admin.com');

        $manager->persist($userEntity);
        $manager->flush();

        $userEntity = new User();
        $userEntity->setUsername('utente');
        $userEntity->setPlainPassword('utente');
        $userEntity->setRoles(['ROLE_USER']);
        $userEntity->setEmail('utente@utente.com');
        $userEntity->setActive(false);

        $manager->persist($userEntity);
        $manager->flush();
    }
}
