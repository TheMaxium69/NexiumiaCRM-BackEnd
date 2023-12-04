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
        $title = $data["title"]; // 1 = appel | 2 = intervention
        $state = "1";
        $duration =  new DateTime($data["duration"]);


        $client = $this->client->find($data['client']);
        $user = $this->user->find($data['user']);

        $intervention = new Intervention();

        $intervention->setDate($date)
            ->setInformation($information)
            ->setState($state)
            ->setClient($client)
            ->setUser($user)
            ->setTitle($title)
            ->setDuration($duration);

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

    //Modifier une intervention
    #[Route('api/intervention/{id}', name: 'edit_intervention', methods: ['PUT'])]
    public function editIntervention($id, Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        $intervention = $this->intervention->find($id);

        if (isset($data["date"])) {
            new DateTime($data['date']);
            if ($data["date"] !== $intervention->getDate()) {
                $intervention->setDate($data["date"]);
            }
        }
        if (isset($data["information"])) {
            if ($data["information"] !== $intervention->getInformation()) {
                $intervention->setInformation($data["information"]);
            }
        }
        if (isset($data["state"])) {
            if ($data["state"] !== $intervention->getState()) {
                $intervention->setState($data["state"]);
            }
        }
        if (isset($data["title"])) {
            if ($data["title"] !== $intervention->getTitle()) {
                $intervention->setTitle($data["title"]);
            }
        }

        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'Profession modifier'
            ]
        );
    }


    //Supprime une intervention
    #[Route('/api/intervention/{id}', name: 'delete_intervention', methods: 'DELETE')]
    public function deleteIntervention($id): Response
    {
        $intervention = $this->intervention->find($id);

        $this->manager->remove($intervention);
        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'Intervention supprimer'
            ]
        );
    }
}
