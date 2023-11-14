<?php

namespace App\Controller;

use App\Entity\Profession;
use App\Repository\ProfessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfessionController extends AbstractController
{

    private $manager;
    private $profession;

    public function __construct(EntityManagerInterface $manager, ProfessionRepository $profession)
    {
        $this->manager = $manager;
        $this->profession = $profession;
    }

    //Création d'une profession
    #[Route('/api/createProfession', name: 'create_profession', methods: 'POST')]
    public function createProfession(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        $name = $data['name'];

        $profession = new Profession();

        $profession->setName($name);

        $this->manager->persist($profession);
        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'Profession créé avec succès'
            ]

        );
    }

    //Liste des professions
    #[Route('/api/professions', name: 'get_all_professions', methods: 'GET')]
    public function getAllProfessions(): Response
    {
        $professions = $this->profession->findAll();

        return $this->json($professions, 200, [], ['groups' => 'allProfessions']);
    }

    //Liste des professions
    #[Route('/api/profession/{id}', name: 'get_one_profession', methods: 'GET')]
    public function getOneProfession($id): Response
    {
        $profession = $this->profession->find($id);

        return $this->json($profession, 200, [], ['groups' => 'oneProfession']);
    }
}
