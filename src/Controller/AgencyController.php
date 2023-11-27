<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Repository\AgencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgencyController extends AbstractController
{

    private $manager;
    private $agency;

    public function __construct(EntityManagerInterface $manager, AgencyRepository $agency)
    {
        $this->manager = $manager;
        $this->agency = $agency;
    }

    //Création d'une agence
    #[Route('/api/createAgency', name: 'create_agency', methods: 'POST')]
    public function createAgency(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $siret = $data['siret'];
        $path = $data['path'];
        $color = $data['color'];
        $email = $data['email'];
        $phone = $data['phone'];
        $tva = $data['tva'];

        $agency = new Agency();

        $agency->setName($name)
            ->setSiret($siret)
            ->setPath($path)
            ->setColor($color)
            ->setEmail($email)
            ->setPhone($phone)
            ->setTva($tva);

        $this->manager->persist($agency);
        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'Agence créé avec succès',
                'agency'=>$agency
            ]

        );
    }

    //Liste des agences
    #[Route('/api/agencies', name: 'get_all_agencie', methods: 'GET')]
    public function getAllAgencies(): Response
    {
        $agencies = $this->agency->findAll();

        return $this->json($agencies, 200, [], ['groups' => 'allAgencies']);
    }

    //Affiche une agence
    #[Route('/api/agency/{id}', name: 'get_one_agency', methods: 'GET')]
    public function getOneAgency($id): Response
    {
        $agency = $this->agency->find($id);

        return $this->json($agency, 200, [], ['groups' => 'oneAgency']);
    }

    //Modifier une agence
    #[Route('api/agency/{id}', name: 'edit_agency', methods: ['PUT'])]
    public function editAgency($id, Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        $agency = $this->agency->find($id);

        if (isset($data["name"])) {
            if ($data["name"] !== $agency->getName()) {
                $agency->setName($data["name"]);
            }
        }
        if (isset($data["siret"])) {
            if ($data["siret"] !== $agency->getSiret()) {
                $agency->setSiret($data["siret"]);
            }
        }
        if (isset($data["path"])) {
            if ($data["path"] !== $agency->getPath()) {
                $agency->setPath($data["path"]);
            }
        }
        if (isset($data["color"])) {
            if ($data["color"] !== $agency->getColor()) {
                $agency->setColor($data["color"]);
            }
        }
        if (isset($data["email"])) {
            if ($data["email"] !== $agency->getEmail()) {
                $agency->setEmail($data["email"]);
            }
        }
        if (isset($data["phone"])) {
            if ($data["phone"] !== $agency->getPhone()) {
                $agency->setPhone($data["phone"]);
            }
        }
        if (isset($data["tva"])) {
            if ($data["tva"] !== $agency->getTva()) {
                $agency->setTva($data["tva"]);
            }
        }


        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'Agence modifier'
            ]
        );
    }

    //Supprime une agence
    #[Route('/api/agency/{id}', name: 'delete_agency', methods: 'DELETE')]
    public function deleteAgency($id): Response
    {
        $agency = $this->agency->find($id);

        $this->manager->remove($agency);
        $this->manager->flush();

        return new JsonResponse(
            [
                'status' => true,
                'message' => 'Agence supprimer'
            ]
        );
    }
}
