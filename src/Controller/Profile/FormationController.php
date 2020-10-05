<?php

namespace App\Controller\Profile;

use App\Entity\User;
use App\Form\Type\UserFormType;
use App\Repository\FormationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Vich\UploaderBundle\Exception\NoFileFoundException;

/**
 * @Route("/api")
 */
class FormationController extends AbstractFOSRestController
{
    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ConsultantController constructor.
     * @param FormationRepository $formationRepository
     */
    public function __construct(FormationRepository $formationRepository, EntityManagerInterface $em)
    {
        $this->formationRepository = $formationRepository;
        $this->em             = $em;
    }

    private function getMessage($message, int $code) {
        return \FOS\RestBundle\View\View::create(['message' => $message, 'statusCode' => $code], $code);
    }


    /**
     * @param Request $request
     * @IsGranted("ROLE_CONSULTANT")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"formation"})
     * @Rest\Post("/admin/users/edit_formation/{id}")
     * @return View
     */
    public function EditInfosProfile(string $id, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user);

        var_dump($id);die;
    }
}
