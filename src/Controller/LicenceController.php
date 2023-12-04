<?php

namespace App\Controller;

use App\Repository\LicenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LicenceController extends AbstractController
{
    private $manager;
    private $licence;

    public function __construct(EntityManagerInterface $manager, LicenceRepository $licence)
    {
        $this->manager = $manager;
        $this->licence = $licence;
    }

    //Liste des licences
    #[Route('/api/licences', name: 'get_all_licences', methods: 'GET')]
    public function getAllLicences(): Response
    {
        $licences = $this->licence->findAll();

        return $this->json($licences, 200, [], ['groups' => 'allLicence']);
    }
}
