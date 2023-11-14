<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\AgencyRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{

    private $manager;
    private $client;
    private $agency;

    public function __construct(EntityManagerInterface $manager, ClientRepository $client, AgencyRepository $agency)
    {
        $this->manager = $manager;
        $this->client = $client;
        $this->agency = $agency;
    }

    //Création d'un client
    #[Route('/api/createClient', name: 'create_client', methods: 'POST')]
    public function createAgency(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $phone = $data['phone'];
        $address = $data['address'];
        $information = $data['information'];
        $state = "Prospet";

        $agency = $this->agency->find($data['agency']);

        $client = new Client();

        $client->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setPhone($phone)
            ->setAddress($address)
            ->setInformation($information)
            ->setState($state)
            ->setAgency($agency);

        $this->manager->persist($client);
        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'Client créé avec succès'
            ]

        );
    }


    //Liste des clients
    #[Route('/api/clients', name: 'get_all_clients', methods: 'GET')]
    public function getAllClients(): Response
    {
        $clients = $this->client->findAll();

        return $this->json($clients, 200, [], ['groups' => 'allClients']);
    }

    //Affiche un client
    #[Route('/api/client/{id}', name: 'get_one_client', methods: 'GET')]
    public function getOneClient($id): Response
    {
        $client = $this->client->find($id);

        return $this->json($client, 200, [], ['groups' => 'oneClient']);
    }
}
