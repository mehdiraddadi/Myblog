<?php
namespace App\Controller\Api;

use App\Entity\User;
use App\Form\Type\LoginFormType;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;



/**
 * @Route("/api")
 */
class SecurityController extends AbstractFOSRestController
{
    /**
     * @param Request $request
     * @Rest\Post("/login")
     * @return View
     */
    public function login(Request $request)
    {
        $user = new User();
        $form = $this->createForm(LoginFormType::class, $user);

        $form->submit($request->request->all());

        return new Response('hello');
    }
}
