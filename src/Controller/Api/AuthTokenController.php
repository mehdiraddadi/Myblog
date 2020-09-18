<?php
namespace App\Controller\Api;

use App\Entity\AuthToken;
use App\Entity\Credentials;
use App\Form\Type\CredentialsType;
use App\Repository\AuthTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var AuthTokenRepository
     */
    private $authTokenRepository;

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
    public function __construct(UserRepository $repository,
                                AuthTokenRepository $authTokenRepository,
                                EntityManagerInterface $em,
                                UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository      = $repository;
        $this->authTokenRepository = $authTokenRepository;
        $this->em                  = $em;
        $this->encoder             = $encoder;
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

        $lastAuthToken = $this->authTokenRepository->findOneByUser($user);
        if($lastAuthToken) {
            $lastAuthToken->setToken(base64_encode(random_bytes(50)));
            $lastAuthToken->setCreatedAt(new \DateTime('now'));
            $this->em->flush();
            return $lastAuthToken;
        }

        $authToken = new AuthToken();
        $authToken->setToken(base64_encode(random_bytes(50)));
        $authToken->setCreatedAt(new \DateTime('now'));
        $authToken->setUser($user);

        $this->em->persist($authToken);
        $this->em->flush();

        return $authToken;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/auth-tokens/{id}")
     */
    public function removeAuthTokenAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $authToken = $em->getRepository('AppBundle:AuthToken')
            ->find($request->get('id'));
        /* @var $authToken AuthToken */

        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($authToken && $authToken->getUser()->getId() === $connectedUser->getId()) {
            $em->remove($authToken);
            $em->flush();
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException();
        }
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    private function invalidCredentials()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
    }
}
