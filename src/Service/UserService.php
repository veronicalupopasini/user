<?php

namespace Esc\User\Service;

use Assert\AssertionFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Esc\User\Entity\ValueObjects\ChangePasswords;
use Esc\User\Entity\ValueObjects\ComparePasswords;
use Esc\User\Entity\ValueObjects\Email;
use Esc\User\Entity\ValueObjects\Username;
use Esc\User\Entity\User;
use Esc\User\Entity\ValueObjects\Roles;
use Esc\User\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class UserService
{
    private $objectManager;
    private $userRepository;

    public function __construct(ObjectManager $manager, UserRepository $userRepository)
    {
        $this->objectManager = $manager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param AttributeBag $data
     * @throws AssertionFailedException
     */
    public function createUser(AttributeBag $data): void
    {
        $user = new User();

        $user->setUsername(new Username($data->get('username')));
        $user->setPlainPassword(new ComparePasswords($data->get('password', ''), $data->get('confirmPassword', '')));
        $user->setEmail(new Email($data->get('email')));
        $user->setActive((bool)$data->get('active', false));
        $roles = new Roles($data->get('roles'));
        $user->setRoles($roles->get());

        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }

    /**
     * @param int $id
     * @param AttributeBag $data
     * @throws AssertionFailedException
     */
    public function updateUser(int $id, AttributeBag $data): void
    {
        $user = $this->userRepository->findOneById($id);

        if ($data->has('password') && !empty($data->get('password'))) {
            $user->setPlainPassword(new ComparePasswords($data->get('password', ''), $data->get('confirmPassword', '')));
        }

        if ($data->has('email')) {
            $user->setEmail(new Email($data->get('email')));
        }

        if ($data->has('active')) {
            $user->setActive((bool)$data->get('active', false));
        }

        if ($data->has('roles')) {
            $roles = new Roles($data->get('roles'));
            $user->setRoles($roles->get());
        }

        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }

    /**
     * @param int $id
     */
    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->findOneById($id);

        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }

    /**
     * @param int $id
     * @param string $newPassword
     * @param string $confirmPassword
     * @param string $oldPassword
     * @throws AssertionFailedException
     */
    public function changeUserPassword(int $id, string $newPassword, string $confirmPassword, string $oldPassword): void
    {
        $user = $this->userRepository->findOneById($id);

        $savedPassword = $user->getPassword();

        $user->setPlainPassword(new ChangePasswords($oldPassword, $newPassword, $confirmPassword, $savedPassword));

        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }
}
