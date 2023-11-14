<?php

namespace App\Entity;

use App\Repository\AgencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AgencyRepository::class)]
class Agency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['allAgencies', 'oneAgency', 'oneClient'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allAgencies', 'oneAgency'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allAgencies', 'oneAgency'])]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allAgencies', 'oneAgency'])]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allAgencies', 'oneAgency'])]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allAgencies', 'oneAgency'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allAgencies', 'oneAgency'])]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    #[Groups(['allAgencies', 'oneAgency'])]
    private ?string $tva = null;

    #[ORM\OneToMany(mappedBy: 'agency', targetEntity: Client::class)]
    #[Groups(['oneAgency'])]
    private Collection $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

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

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(string $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setAgency($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getAgency() === $this) {
                $client->setAgency(null);
            }
        }

        return $this;
    }
}
