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

    //Modifier une client
    #[Route('api/client/{id}', name: 'edit_client', methods: ['PUT'])]
    public function editClient($id, Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        $client = $this->client->find($id);

        if (isset($data["firstName"])) {
            if ($data["firstName"] !== $client->getFirstName()) {
                $client->setFirstName($data["firstName"]);
            }
        }
        if (isset($data["lastName"])) {
            if ($data["lastName"] !== $client->getLastName()) {
                $client->setLastName($data["lastName"]);
            }
        }
        if (isset($data["email"])) {
            if ($data["email"] !== $client->getEmail()) {
                $client->setEmail($data["email"]);
            }
        }
        if (isset($data["phone"])) {
            if ($data["phone"] !== $client->getPhone()) {
                $client->setPhone($data["phone"]);
            }
        }
        if (isset($data["address"])) {
            if ($data["address"] !== $client->getAddress()) {
                $client->setAddress($data["address"]);
            }
        }
        if (isset($data["information"])) {
            if ($data["information"] !== $client->getInformation()) {
                $client->setInformation($data["information"]);
            }
        }
        if (isset($data["state"])) {
            if ($data["state"] !== $client->getState()) {
                $client->setState($data["state"]);
            }
        }

        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'Client modifier'
            ]
        );
    }

    //Supprime une client
    #[Route('/api/client/{id}', name: 'delete_client', methods: 'DELETE')]
    public function deleteClient($id): Response
    {
        $client = $this->client->find($id);

        $this->manager->remove($client);
        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'Client supprimer'
            ]
        );
    }
}
