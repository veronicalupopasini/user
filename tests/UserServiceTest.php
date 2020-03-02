<?php

namespace Test\Esc\User;

use Assert\AssertionFailedException;
use Doctrine\ORM\EntityManagerInterface;
use Esc\User\Entity\User;
use Esc\User\Repository\UserRepository;
use Esc\User\Service\UserService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class UserServiceTest extends TestCase
{
    /**
     * @throws AssertionFailedException
     */
    public function testCreateUser(): void
    {
        $user = $this->getMockBuilder(User::class)
            ->getMock();

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $user->expects($this->once())
            ->method('setUsername')
            ->with('foo');

        $user->expects($this->once())
            ->method('setPlainPassword')
            ->with('bar');

        $user->expects($this->once())
            ->method('setEmail')
            ->with('baz@baz.it');

        $user->expects($this->once())
            ->method('setActive')
            ->with(true);

        $user->expects($this->once())
            ->method('setRoles')
            ->with([1]);

        $entityManager->expects($this->once())
            ->method('persist')
            ->with($user);

        $entityManager->expects($this->once())
            ->method('flush');

        $userBag = new AttributeBag();
        $userData = [
            'username' => 'foo',
            'password' => 'bar',
            'confirmPassword' => 'bar',
            'email' => 'baz@baz.it',
            'active' => true,
            'roles' => [1],
        ];
        $userBag->initialize($userData);

        $service = new UserService($entityManager, $userRepository, $user);

        $service->createUser($userBag);

    }

    /**
     * @throws AssertionFailedException
     */
    public function testUpdateUser(): void
    {
        $userBag = new AttributeBag();
        $data = [
            'password' => 'bar',
            'confirmPassword' => 'bar',
            'email' => 'foo@foo.it',
        ];
        $userBag->initialize($data);


        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $user = $this->getMockBuilder(User::class)
            ->getMock();

        $user->expects($this->once())
            ->method('setEmail')
            ->with('foo@foo.it');

        $user->expects($this->once())
            ->method('setPlainPassword')
            ->with('bar');

        $userRepository->method('getOneById')
            ->with(1)
            ->willReturn($user);

        $entityManager->expects($this->once())
            ->method('persist')
            ->with($user);

        $entityManager->expects($this->once())
            ->method('flush');

        $service = new UserService($entityManager, $userRepository, $user);

        $service->updateUser(1, $userBag);
    }

    public function testDeleteUser(): void
    {
        $user = $this->getMockBuilder(User::class)
            ->getMock();

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository->method('getOneById')
            ->with(1)
            ->willReturn($user);

        $entityManager->expects($this->once())
            ->method('remove')
            ->with($user);

        $entityManager->expects($this->once())
            ->method('flush');

        $service = new UserService($entityManager, $userRepository, $user);

        $service->deleteUser(1);
    }

    public function testDeleteUserThrowException(): void
    {
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository->method('getOneById')
            ->with(1)
            ->willThrowException(new \RuntimeException());

        $this->expectException(\RuntimeException::class);

        $service = new UserService($entityManager, $userRepository, new User());

        $service->deleteUser(1);
    }
}