<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProfessionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $manager;

    private $user;

    private $profession;

    public function __construct(EntityManagerInterface $manager, UserRepository $user, ProfessionRepository $profession)
    {
        $this->manager = $manager;

        $this->user = $user;

        $this->profession = $profession;
    }


    //Création d'un utilisateur
    #[Route('/userCreate', name: 'user_create', methods: 'POST')]
    public function userCreate(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'];

        $lastName = $data['lastName'];

        $email = $data['email'];

        $password = $data['password'];

        $profession = $this->profession->find($data['profession']);

        //Vérifier si l'email existe déjà

        $email_exist = $this->user->findOneByEmail($email);

        if ($email_exist) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Cet email existe déjà, veuillez le changer'
                ]

            );
        } else {
            $user = new User();

            $user->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPassword(sha1($password))
                ->setProfession($profession);

            $this->manager->persist($user);

            $this->manager->flush();

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'L\'utilisateur créé avec succès'
                ]

            );
        }
    }



    //Liste des utilisateurs
    #[Route('/api/getAllUsers', name: 'get_allusers', methods: 'GET')]
    public function getAllUsers(): Response
    {
        $users = $this->user->findAll();

        return $this->json($users, 200);
    }
}
