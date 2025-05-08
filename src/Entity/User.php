<?php

namespace App\Entity;

use App\Entity\Fiche;
use App\Entity\RapportEmpotage;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[Vich\UploadableField(mapping: 'douane', fileNameProperty: 'imageName')]
  
    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'L\'email est requis.')]
    #[Assert\Email(
        message: 'L\'email "{{ value }}" n\'est pas un email valide.',
        mode: 'strict'
    )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le numéro de téléphone est requis.')]
    #[Assert\Regex(
        pattern: '/^(?:\+225|0)(01|05|07)\d{8}$/',
        message: 'Le numéro doit être un numéro ivoirien valide (ex: 0102030405 ou +2250102030405).'
    )]
    private ?string $contact = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Fiche::class)]
    private Collection $fiches;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: RapportEmpotage::class)]
    private Collection $rapportEmpotages;

    #[ORM\Column(nullable: true)]
    private ?int $NombreDossier = null;

    #[ORM\OneToMany(mappedBy: 'cda', targetEntity: Fiche::class)]
    private Collection $demande;
    #[ORM\Column(nullable: true)]
    public function __construct()
    {
        $this->date = new \DateTimeImmutable();
        $this->fiches = new ArrayCollection();
        $this->rapportEmpotages = new ArrayCollection();
        $this->demande = new ArrayCollection();
    }
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Fiche>
     */
    public function getFiches(): Collection
    {
        return $this->fiches;
    }

    public function addFich(Fiche $fich): self
    {
        if (!$this->fiches->contains($fich)) {
            $this->fiches->add($fich);
            $fich->setUser($this);
        }

        return $this;
    }

    public function removeFich(Fiche $fich): self
    {
        if ($this->fiches->removeElement($fich)) {
            // set the owning side to null (unless already changed)
            if ($fich->getUser() === $this) {
                $fich->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RapportEmpotage>
     */
    public function getRapportEmpotages(): Collection
    {
        return $this->rapportEmpotages;
    }

    public function addRapportEmpotage(RapportEmpotage $rapportEmpotage): self
    {
        if (!$this->rapportEmpotages->contains($rapportEmpotage)) {
            $this->rapportEmpotages->add($rapportEmpotage);
            $rapportEmpotage->setUser($this);
        }

        return $this;
    }

    public function removeRapportEmpotage(RapportEmpotage $rapportEmpotage): self
    {
        if ($this->rapportEmpotages->removeElement($rapportEmpotage)) {
            // set the owning side to null (unless already changed)
            if ($rapportEmpotage->getUser() === $this) {
                $rapportEmpotage->setUser(null);
            }
        }

        return $this;
    }

    public function getNombreDossier(): ?int
    {
        return $this->NombreDossier;
    }

    public function setNombreDossier(?int $NombreDossier): self
    {
        (string) $this->NombreDossier = $NombreDossier;

        return $this;
    }
    

    public function __toString()
    {
        return $this->NombreDossier;
    }

    /**
     * @return Collection<int, Fiche>
     */
    public function getDemande(): Collection
    {
        return $this->demande;
    }

    public function addDemande(Fiche $demande): self
    {
        if (!$this->demande->contains($demande)) {
            $this->demande->add($demande);
            $demande->setCda($this);
        }

        return $this;
    }

    public function removeDemande(Fiche $demande): self
    {
        if ($this->demande->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getCda() === $this) {
                $demande->setCda(null);
            }
        }

        return $this;
    }

   

 

    
}
