<?php

namespace App\Entity;

use App\Repository\InterventionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InterventionRepository::class)]
class Intervention
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['allInterventions', 'oneIntervention', 'oneTechnicien', 'oneAdmin'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['allInterventions', 'oneIntervention', 'oneTechnicien', 'oneAdmin'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['allInterventions', 'oneIntervention', 'oneTechnicien', 'oneAdmin'])]
    private ?string $information = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allInterventions', 'oneIntervention', 'oneTechnicien', 'oneAdmin'])]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    #[Groups(['oneIntervention','allInterventions', 'oneTechnicien', 'oneAdmin'])]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    #[Groups(['oneIntervention','allInterventions'])]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allInterventions', 'oneIntervention', 'oneTechnicien', 'oneAdmin'])]
    private ?string $title = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): static
    {
        $this->information = $information;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
