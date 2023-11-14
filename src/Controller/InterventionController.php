<?php

namespace App\Controller;

use App\Entity\Intervention;
use App\Repository\ClientRepository;
use App\Repository\InterventionRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InterventionController extends AbstractController
{
    private $manager;
    private $intervention;
    private $client;
    private $user;

    public function __construct(EntityManagerInterface $manager, InterventionRepository $intervention, ClientRepository $client, UserRepository $user)
    {
        $this->manager = $manager;
        $this->intervention = $intervention;
        $this->client = $client;
        $this->user = $user;
    }

    //Création d'une intervention
    #[Route('/api/createIntervention', name: 'create_intervention', methods: 'POST')]
    public function createIntervention(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        $date = new DateTime($data['date']);
        $information = $data['information'];
        $state = "En cours";

        $client = $this->client->find($data['client']);
        $user = $this->user->find($data['user']);

        $intervention = new Intervention();

        $intervention->setDate($date)
            ->setInformation($information)
            ->setState($state)
            ->setClient($client)
            ->setUser($user);

        $this->manager->persist($intervention);
        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'Intervention créé avec succès'
            ]

        );
    }

    //Liste des interventions
    #[Route('/api/interventions', name: 'get_all_interventions', methods: 'GET')]
    public function getAllInterventions(): Response
    {
        $interventions = $this->intervention->findAll();

        return $this->json($interventions, 200, [], ['groups' => 'allInterventions']);
    }

    //Affiche une intervention
    #[Route('/api/intervention/{id}', name: 'get_one_intervention', methods: 'GET')]
    public function getOneIntervention($id): Response
    {
        $intervention = $this->intervention->find($id);

        return $this->json($intervention, 200, [], ['groups' => 'oneIntervention']);
    }
}
