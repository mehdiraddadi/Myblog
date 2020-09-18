<?php
namespace App\Security;

use App\Repository\AuthTokenRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AuthTokenUserProvider implements UserProviderInterface
{
    private $authTokenRepository;
    private $userRepository;

    public function __construct(AuthTokenRepository $authTokenRepository, UserRepository $userRepository)
    {
        $this->authTokenRepository = $authTokenRepository;
        $this->userRepository      = $userRepository;
    }

    public function getAuthToken($authTokenHeader)
    {
        return $this->authTokenRepository->findOneBy(["token" => $authTokenHeader]);
    }

    public function loadUserByUsername($username)
    {
        return $this->userRepository->findOneBy(["username" => $username]);
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'App\Entity\User' === $class;
    }
}
