<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 * @ApiResource(
 *      attributes={
 *          "order"= {
 *              "titre":"ASC"
 *          }
 *      },
 *      collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/livres",
 *              "normalization_context"= {
 *                  "groups"={"get_role_adherent"}
 *              }
 *          },
 *          "post"={
 *              "method"="POST",
 *              "security"="is_granted('ROLE_MANAGER')",
 *              "security_message"="Vous n'avez pas les droits d'acceder à cette ressource"
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/livres/{id}", 
 *              "normalization_context"= {
 *                  "groups"={"get_role_adherent"}
 *              }
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "path"="/livres/{id}",
 *              "security"="is_granted('ROLE_MANAGER')",
 *              "security_message"="Vous n'avez pas les droits d'acceder à cette ressource",
 *              "denormalization_context"= {
 *                  "groups"={"put_manager"}
 *              }
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "path"="/admin/livres/{id}",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas les droits d'acceder à cette ressource",
 *          }
 *          
 *      }
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "titre": "ipartial",
 *          "auteur": "exact",
 *          "genre": "exact"
 *      }
 * )
 * 
 * 
 * @ApiFilter(
 *      OrderFilter::class,
 *      properties={
 *          "titre"="asc",
 *          "prix",
 *          "auteur.nom"="desc"
 *      }
 * )
 * 
 * @ApiFilter(
 *      PropertyFilter::class,
 *      arguments={
 *          "parameterName":"properties",
 *          "overrideDefaultProperties": false,
 *          "whitelist"= {
 *                  "isbn",
 *                  "titre",
 *                  "prix"
 *          }
 *      }
 * )
 */
class Livre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent", "put_manager"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent", "put_manager"})
     */
    private $titre;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"get_role_manager", "put_admin"})
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_role_adherent", "put_manager"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=Editeur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_role_adherent", "put_manager"})
     */
    private $editeur;

    /**
     * @ORM\ManyToOne(targetEntity=Auteur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_role_adherent", "put_manager"})
     */
    private $auteur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"get_role_adherent", "put_manager"})
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_role_adherent", "put_manager"})
     */
    private $langue;

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="livre")
     * @Groups({"get_role_manager"})
     */
    private $prets;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(?string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->setLivre($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getLivre() === $this) {
                $pret->setLivre(null);
            }
        }

        return $this;
    }
}
