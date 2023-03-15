<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FamilyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FamilyRepository::class)]
#[ApiResource]
class Family
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'famille', targetEntity: Character::class)]
    private Collection $personnage;

    public function __construct()
    {
        $this->personnage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getPersonnage(): Collection
    {
        return $this->personnage;
    }

    public function addPersonnage(Character $personnage): self
    {
        if (!$this->personnage->contains($personnage)) {
            $this->personnage->add($personnage);
            $personnage->setFamille($this);
        }

        return $this;
    }

    public function removePersonnage(Character $personnage): self
    {
        if ($this->personnage->removeElement($personnage)) {
            // set the owning side to null (unless already changed)
            if ($personnage->getFamille() === $this) {
                $personnage->setFamille(null);
            }
        }

        return $this;
    }
}
