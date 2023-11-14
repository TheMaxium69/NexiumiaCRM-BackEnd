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

    //Création d'un technicien
    #[Route('/api/createTechnicien', name: 'create_technicien', methods: 'POST')]
    public function createTechnicien(Request $request): Response
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
                ->setProfession($profession)
                ->setRoles(['ROLE_TECHNICIEN']);

            $this->manager->persist($user);
            $this->manager->flush();

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Technicien créé avec succès'
                ]

            );
        }
    }

    //Création d'un admin
    #[Route('/api/createAdmin', name: 'create_admin', methods: 'POST')]
    public function createAdmin(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $password = $data['password'];
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
                ->setRoles(['ROLE_ADMIN']);

            $this->manager->persist($user);
            $this->manager->flush();

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Admin créé avec succès'
                ]

            );
        }
    }

    //Liste des utilisateurs
    #[Route('/api/users', name: 'get_all_users', methods: 'GET')]
    public function getAllUsers(): Response
    {
        $users = $this->user->findAll();

        return $this->json($users, 200, [], ['groups' => 'allUsers']);
    }

    //Liste des techniciens
    #[Route('/api/techniciens', name: 'get_all_techniciens', methods: 'GET')]
    public function getAllTechniciens(): Response
    {
        $users = $this->user->findUsers('ROLE_TECHNICIEN');

        return $this->json($users, 200, [], ['groups' => 'allTechniciens']);
    }

    //Liste des admins
    #[Route('/api/admins', name: 'get_all_admins', methods: 'GET')]
    public function getAllAdmins(): Response
    {
        $users = $this->user->findUsers('ROLE_ADMIN');

        return $this->json($users, 200, [], ['groups' => 'allAdmins']);
    }

    //Affiche un user
    #[Route('/api/user/{id}', name: 'get_one_user', methods: 'GET')]
    public function getOneUser($id): Response
    {
        $user = $this->user->find($id);
        $roles = $user->getRoles();

        foreach ($roles as $role) {
            if ($role == "ROLE_TECHNICIEN") {
                return $this->json($user, 200, [], ['groups' => 'oneTechnicien']);
            } else if ($role == "ROLE_ADMIN") {
                return $this->json($user, 200, [], ['groups' => 'oneAdmin']);
            }
        }

        return new JsonResponse(
            [
                'status' => false,
                'message' => 'Erreur'
            ]
        );
    }

    #[Route('/api/testConnection', name: 'app_testConnection')]
    public function testConnection(): Response
    {
        /**@var User $user  */
        $user = $this->getUser();

        if ($user === null) {
            return $this->json('non connecté');
        } else {


            return $this->json('connecté en tant que ' . $user->getEmail());
        }
    }

    //Supprime un user
    #[Route('/api/deleteUser', name: 'delete_user', methods: 'DELETE')]
    public function deleteUser(): Response
    {
        return new JsonResponse(
            [
                'status' => false,
                'message' => 'User supprimer'
            ]
        );
    }
}
