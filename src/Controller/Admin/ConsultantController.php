<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Type\UserFormTYpe;
use App\Form\Type\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api")
 */
class ConsultantController extends AbstractFOSRestController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ConsultantController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em             = $em;
    }

    /**
     * @param Request $request
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Get("/admin/users")
     * @return View
     */
    public function index()
    {
        $users = $this->userRepository->findAll();
        return $users;
    }

    /**
     * @param Request $request
     * @IsGranted("ROLE_CONSULTANT")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/admin/users/edit_photo")
     * @return View
     */
    public function postImageProfile(Request $request)
    {
        $user = $this->getUser();
        if(!$user) {
            $message = 'User not found!';
            $this->getMessage($message);
        }
        $oldImage = $user->getFilename();
        if($oldImage) {
            if(file_exists($this->getParameter('images_directory').'/'.$oldImage)) {
                unlink($this->getParameter('images_directory').'/'.$oldImage);
            }
        }
        $imageFile = $request->files->get('imageFile');

        if($imageFile && $imageFile instanceof UploadedFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $user->getFirstname().'-'.$user->getlastname().'.'.$imageFile->guessExtension();
            // Move the file to the directory where brochures are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                return $this->getMessage($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
            $user->setFilename($newFilename);
            $this->em->flush();
        }

        return $this->getMessage('Image Uploaded', Response::HTTP_OK);
    }


    private function getMessage($message, int $code) {
        return \FOS\RestBundle\View\View::create(['message' => $message, 'statusCode' => $code], $code);
    }
}
