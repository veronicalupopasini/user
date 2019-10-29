<?php

namespace Esc\User\Controller;

use Assert\AssertionFailedException;
use Esc\User\Repository\UserRepository;
use Esc\User\Service\UserService;
use Esc\RequestParams;
use Esc\Result;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class UserController extends AbstractController
{
    private $userRepository;
    private $userService;

    public function __construct(UserRepository $userRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    /**
     * @Route("/api/user", name="fetch_users", methods={"GET"})
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @param Result $result
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function users(Request $request, NormalizerInterface $normalizer, Result $result): Response
    {
        $requestParams = RequestParams::fromRequest($request);

        try {
            $result->setData($this->userRepository->findByCriteria($requestParams));
            $result->setTotalRows($normalizer->normalize(
                $this->userRepository->countByCriteria($requestParams->get('filters', []))
            ));
            return new JsonResponse($result->toArray());
        } catch (Exception $e) {
            $result->setMessage($e->getMessage());
            return new JsonResponse($result->toArray(), 400);
        }
    }

    /**
     * @Route("/api/user/{id}", name="get_user", methods={"GET"})
     * @param int $id
     * @param NormalizerInterface $normalizer
     * @param Result $result
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function findUser(int $id, NormalizerInterface $normalizer, Result $result): Response
    {
        try {
            $user = $this->userRepository->readOneById($id);
            if ($user) {
                $result->setData($normalizer->normalize($user));
            } else {
                throw new Exception('User not found');
            }

            return new JsonResponse($result->toArray());
        } catch (Exception $e) {
            $result->setMessage($e->getMessage());
            return new JsonResponse($result->toArray(), 400);
        }
    }

    /**
     * @Route("/api/user", name="create_user", methods={"POST"})
     * @param Request $request
     * @param Result $result
     * @return JsonResponse
     * @throws AssertionFailedException
     */
    public function createUser(Request $request, Result $result): Response
    {
        try {
            $userData = $request->request->all();
            $userDataBag = new AttributeBag();
            $userDataBag->initialize($userData);

            $this->userService->createUser($userDataBag);

            return new JsonResponse($result->toArray());
        } catch (Exception $e) {
            $result->setMessage($e->getMessage());
            return new JsonResponse($result->toArray(), 422);
        }
    }

    /**
     * @Route("/api/user/{id}", name="update_user", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @param Result $result
     * @return JsonResponse
     * @throws AssertionFailedException
     */
    public function updateUser(int $id, Request $request, Result $result): Response
    {
        try {
            $userData = $request->request->all();
            $userDataBag = new AttributeBag();
            $userDataBag->initialize($userData);

            $this->userService->updateUser($id, $userDataBag);

            return new JsonResponse($result->toArray());
        } catch (Exception $e) {
            $result->setMessage($e->getMessage());
            return new JsonResponse($result->toArray(), 422);
        }
    }

    /**
     * @Route("/api/user/{id}", name="delete_user", methods={"DELETE"})
     * @param int $id
     * @param Result $result
     * @return JsonResponse
     */
    public function deleteUser(int $id, Result $result): Response
    {
        try {
            $this->userService->deleteUser($id);

            return new JsonResponse($result->toArray());
        } catch (Exception $e) {
            $result->setMessage($e->getMessage());
            return new JsonResponse($result->toArray(), 422);
        }
    }

    /**
     * @Route("/api/user/changepassword/{id}", name="change_user_password", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @param Result $result
     * @return JsonResponse
     * @throws AssertionFailedException
     */
    public function changePassword(int $id, Request $request, Result $result): Response
    {
        try {
            $oldPassword = $request->get('oldPassword');
            $password = $request->get('password');
            $confirmPassword = $request->get('confirmPassword');

            $this->userService->changeUserPassword($id, $password, $confirmPassword, $oldPassword);

            return new JsonResponse($result->toArray());
        } catch (Exception $e) {
            $result->setMessage($e->getMessage());
            return new JsonResponse($result->toArray(), 422);
        }
    }
}