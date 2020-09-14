<?php
namespace App\Controller\Api;

use App\Entity\AuthToken;
use App\Entity\Credentials;
use App\Entity\User;
use App\Form\Type\CredentialsType;
use App\Form\Type\LoginFormType;
use App\Repository\PropertyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



/**
 * @Route("/api")
 */
class AuthTokenController extends AbstractFOSRestController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $encoder;

    /**
     * AuthTokenController constructor.
     * @param UserRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserRepository $repository, EntityManagerInterface $em,
                                UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $repository;
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * @param Request $request
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"auth-token"})
     * @Rest\Post("/auth-tokens/login")
     * @return View
     */
    public function postAuthTokens(Request $request)
    {
        $credentials = new Credentials();
        $form = $this->createForm(CredentialsType::class, $credentials);

        $form->submit($request->request->all());
        if(!$form->isValid()) {
            return $form;
        }
        $user = $this->userRepository->findOneBy(['username' => $credentials->getLogin()]);
        if(!$user) {
            return $this->invalidCredentials();
        }

        $isPasswordValid = $this->encoder->isPasswordValid($user, $credentials->getPassword());
        if(!$isPasswordValid){
            return $this->invalidCredentials();
        }

        $authToken = new AuthToken();
        $authToken->setValue(base64_encode(random_bytes(50)));
        $authToken->setCreatedAt(new \DateTime('now'));
        $authToken->setUser($user);

        $this->em->persist($authToken);
        $this->em->flush();

        return $authToken;
    }

    /**
     * @param Request $request
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Get("/user/{username}")
     * @return View
     */
    public function getUserConnect($username)
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        dump($user);die;
        if(!$user) {
            return $this->invalidCredentials();
        }


        return $user;
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    private function invalidCredentials()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
    }
}
