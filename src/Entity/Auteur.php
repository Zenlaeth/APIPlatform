<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuteurRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 * @ApiResource(
 *  attributes=
 *      {
 *      "order"= {"nom":"ASC},
 *      "pagination_enabled"=false
 *      },
 *  collectionOperations={
 *      "get"=:{
 *          "method"="GET",
 *          "normalization_context"=
 *              {
 *                  "groups"={"get_auteur_role_adherent"}
 *              }
 *      },
 *      "post"={
 *          "method"="POST",
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas les droits d'acceder à cette ressource"
 *          "denormalization_context"= 
 *          {
 *                  "groups"={"put_manager"}
 *          }
 *      }
 *  },
 *   itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "normalization_context"= {
 *                  "groups"={"get_role_adherent"}
 *              }
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas les droits d'acceder à cette ressource",
 *              "denormalization_context"= {
 *                  "groups"={"put_role_manager"}
 *              }
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas les droits d'acceder à cette ressource",
 *          }
 *          
 *      }
 * 
 * }
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "nom": "ipartial",
 *          "prenom": "exact",
 *          "nationalite": "exact"
 *      }
 * )
 */
class Auteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_auteur_role_adherent"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_auteur_role_adherent", "put_role_manager"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_auteur_role_adherent", "put_role_manager"})
     */
    private $prenom;

    /**
     * @ORM\ManyToOne(targetEntity=Nationalite::class, inversedBy="auteurs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_auteur_role_adherent", "put_role_manager"})
     */
    private $Relation;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="auteur")
     * @Groups({"get_auteur_role_adherent"})
     */
    private $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getRelation(): ?Nationalite
    {
        return $this->Relation;
    }

    public function setRelation(?Nationalite $Relation): self
    {
        $this->Relation = $Relation;

        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setAuteur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * Retourne le nombre de livres de l'auteur
     * @Groups({"get"})
     *
     * @return integer
     */
    public function getNbLivres() : int
    {
        return $this->livres->count();
    }

    /**
     * Retourne le nombre de livres disponibles de cet auteur
     * @Groups({"get"})
     * 
     * @return integer
     */
    public function getNbLivresDispo() : int
    {
        return array_reduce($this->livres->toArray(), function($nb, $livre){
            return $nb + ($livre->getDispo() == true ? 1: 0);
        }, 0);
    }
}
