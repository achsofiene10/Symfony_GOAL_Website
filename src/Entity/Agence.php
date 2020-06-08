<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgenceRepository")
 */
class Agence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matriculeFiscale;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     */
    private $nom;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Utilisateur", mappedBy="agence", cascade={"persist", "remove"})
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Terrain", mappedBy="agence", orphanRemoval=true)
     */
    private $terrains;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $num_tel;

    public function __construct()
    {
        $this->terrains = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatriculeFiscale(): ?string
    {
        return $this->matriculeFiscale;
    }

    public function setMatriculeFiscale(string $matriculeFiscale): self
    {
        $this->matriculeFiscale = $matriculeFiscale;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        // set (or unset) the owning side of the relation if necessary
        $newAgence = $utilisateur === null ? null : $this;
        if ($newAgence !== $utilisateur->getAgence()) {
            $utilisateur->setAgence($newAgence);
        }

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|Terrain[]
     */
    public function getTerrains(): Collection
    {
        return $this->terrains;
    }

    public function addTerrain(Terrain $terrain): self
    {
        if (!$this->terrains->contains($terrain)) {
            $this->terrains[] = $terrain;
            $terrain->setAgence($this);
        }

        return $this;
    }

    public function removeTerrain(Terrain $terrain): self
    {
        if ($this->terrains->contains($terrain)) {
            $this->terrains->removeElement($terrain);
            // set the owning side to null (unless already changed)
            if ($terrain->getAgence() === $this) {
                $terrain->setAgence(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        return $this->nom;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(?string $num_tel): self
    {
        $this->num_tel = $num_tel;

        return $this;
    }

}
