<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['allClients', 'oneIntervention', 'oneClient', 'oneAgency', 'allAgencies'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allClients', 'oneClient'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allClients', 'oneClient'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allClients', 'oneClient'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allClients', 'oneClient'])]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allClients', 'oneClient'])]
    private ?string $address = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['allClients', 'oneClient'])]
    private ?string $information = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allClients', 'oneClient'])]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    #[Groups(['allClients', 'oneClient'])]
    private ?Agency $agency = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Intervention::class)]
    private Collection $interventions;

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

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

    public function getAgency(): ?Agency
    {
        return $this->agency;
    }

    public function setAgency(?Agency $agency): static
    {
        $this->agency = $agency;

        return $this;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): static
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->setClient($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): static
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getClient() === $this) {
                $intervention->setClient(null);
            }
        }

        return $this;
    }
}
