<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TerrainRepository")
 */
class Terrain
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
    private $nom;

    /**
     * @ORM\Column(type="float")
     */
    private $Largeur;

    /**
     * @ORM\Column(type="float")
     */
    private $Longueur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agence", inversedBy="terrains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agence;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="terrain", orphanRemoval=true)
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="terrain", orphanRemoval=true)
     */
    private $notes;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLargeur(): ?float
    {
        return $this->Largeur;
    }

    public function setLargeur(float $Largeur): self
    {
        $this->Largeur = $Largeur;

        return $this;
    }

    public function getLongueur(): ?float
    {
        return $this->Longueur;
    }

    public function setLongueur(float $Longueur): self
    {
        $this->Longueur = $Longueur;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAgence(): ?agence
    {
        return $this->agence;
    }

    public function setAgence(?agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setTerrain($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getTerrain() === $this) {
                $reservation->setTerrain(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setTerrain($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getTerrain() === $this) {
                $note->setTerrain(null);
            }
        }

        return $this;
    }


    public function getNoteStade(): ?float
    {
        
        $notesStade=$this->notes;
        $i=0;
        $j=0;
        foreach ($notesStade as $value){
         $j=$j+1;
         $i=$i+$value;   
        }
        if($j==0)
        {
            return 0;

        }
        else
        {
            return $i/$j;

        }
    }

    public function __toString()
    {
        return $this->nom;
    }
}
