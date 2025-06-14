<?php

namespace App\Entity;

use App\Repository\FicheRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FicheRepository::class)]
#[Vich\Uploadable]
class Fiche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ?string $num_fiche = null;


    #[ORM\Column(length: 255)]
    private ?string $nom_cda = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_exportateur = null;

    #[ORM\Column(length: 255)]
    private ?string $nature_marchandise = null;

    #[ORM\Column(length: 255)]
    private ?string $colis_marchandise = null;

    #[ORM\Column(length: 255)]
    private ?string $poidsNet_marchandise = null;

    #[ORM\Column(length: 255)]
    private ?string $conditionnement_marchandise = null;

    #[ORM\Column(length: 255)]
    private ?string $lieuEmpotage_marchandise = null;

    #[ORM\Column(length: 255)]
    private ?string $nconteneurs = null;

    #[ORM\Column(length: 255)]
    private ?string $typeTC = null;

    #[ORM\Column(length: 255)]
    private ?string $nplombs = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\ManyToOne(inversedBy: 'fiches')]
    private ?user $user = null;

    #[ORM\Column]
    private ?bool $statut = null;

    #[ORM\ManyToOne(inversedBy: 'demande')]
    private ?user $cda = null;
     #[ORM\Column(type: 'json', nullable: true)]
    private array $fichiers = [];
#[ORM\Column(type: 'float', nullable: true)]
private ?float $latitude = null;

#[ORM\Column(type: 'float', nullable: true)]
private ?float $longitude = null;


    public function __construct()
    {
        $this->date = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
       public function getFichiers(): array
    {
        return $this->fichiers;
    }

    public function setFichiers(array $fichiers): self
    {
        $this->fichiers = $fichiers;
        return $this;
    }

    public function addFichier(string $nom): self
    {
        $this->fichiers[] = $nom;
        return $this;
    }
    public function setNumFiche(string $num_fiche): self
    {
        $this->num_fiche = $num_fiche;

        return $this;
    }

    public function getNumFiche(): ?string
    {
        return $this->num_fiche;
    }
   
    public function getNomCda(): ?string
    {
        return $this->nom_cda;
    }

    public function setNomCda(string $nom_cda): self
    {
        $this->nom_cda = $nom_cda;

        return $this;
    }

    public function getNomExportateur(): ?string
    {
        return $this->nom_exportateur;
    }

    public function setNomExportateur(string $nom_exportateur): self
    {
        $this->nom_exportateur = $nom_exportateur;

        return $this;
    }

    public function getNatureMarchandise(): ?string
    {
        return $this->nature_marchandise;
    }

    public function setNatureMarchandise(string $nature_marchandise): self
    {
        $this->nature_marchandise = $nature_marchandise;

        return $this;
    }

    public function getColisMarchandise(): ?string
    {
        return $this->colis_marchandise;
    }

    public function setColisMarchandise(string $colis_marchandise): self
    {
        $this->colis_marchandise = $colis_marchandise;

        return $this;
    }

    public function getPoidsNetMarchandise(): ?string
    {
        return $this->poidsNet_marchandise;
    }

    public function setPoidsNetMarchandise(string $poidsNet_marchandise): self
    {
        $this->poidsNet_marchandise = $poidsNet_marchandise;

        return $this;
    }

    public function getConditionnementMarchandise(): ?string
    {
        return $this->conditionnement_marchandise;
    }

    public function setConditionnementMarchandise(string $conditionnement_marchandise): self
    {
        $this->conditionnement_marchandise = $conditionnement_marchandise;

        return $this;
    }

    public function getLieuEmpotageMarchandise(): ?string
    {
        return $this->lieuEmpotage_marchandise;
    }

    public function setLieuEmpotageMarchandise(string $lieuEmpotage_marchandise): self
    {
        $this->lieuEmpotage_marchandise = $lieuEmpotage_marchandise;

        return $this;
    }

    public function getNconteneurs(): ?string
    {
        return $this->nconteneurs;
    }

    public function setNconteneurs(string $nconteneurs): self
    {
        $this->nconteneurs = $nconteneurs;

        return $this;
    }

    public function getTypeTC(): ?string
    {
        return $this->typeTC;
    }

    public function setTypeTC(string $typeTC): self
    {
        $this->typeTC = $typeTC;

        return $this;
    }

    public function getNplombs(): ?string
    {
        return $this->nplombs;
    }

    public function setNplombs(string $nplombs): self
    {
        $this->nplombs = $nplombs;

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCda(): ?user
    {
        return $this->cda;
    }

    public function setCda(?user $cda): self
    {
        $this->cda = $cda;

        return $this;
    }
   
 public function getLatitude(): ?float
{
    return $this->latitude;
}

public function setLatitude(?float $latitude): self
{
    $this->latitude = $latitude;
    return $this;
}

public function getLongitude(): ?float
{
    return $this->longitude;
}

public function setLongitude(?float $longitude): self
{
    $this->longitude = $longitude;
    return $this;
}
}
