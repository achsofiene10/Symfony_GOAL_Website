<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Client;
use App\Entity\Agence;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     */
    private $motPasse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Client", inversedBy="utilisateur", cascade={"persist", "remove"})
     */
    private $client;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Agence", inversedBy="utilisateur", cascade={"persist", "remove"})
     */
    private $agence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMotPasse(): ?string
    {
        return $this->motPasse;
    }

    public function setMotPasse(string $motPasse): self
    {
        $this->motPasse = $motPasse;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    public function getRoles()
    {
        return [$this->role];
    }

    public function getPassword()
    {
        return $this->motPasse;
    }

    public function getUsername()
    {
        return $this->email;
    }


    public function getSalt()
    {
        return null;
    }


    public function eraseCredentials()
    {

    }

    public function __toString()
    {
        return $this->email;
    }
    
}
