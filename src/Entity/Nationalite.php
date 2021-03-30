<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NationaliteRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=NationaliteRepository::class)
 * @ApiResource(
 *  attributes=
 *      {
 *      "order"= {"libelle":"ASC"}
 *      },
 *  collectionOperations={
 *      "get"={
 *          "method"="GET",
 *          "path"="/nationalites",
 *          "normalization_context"=
 *              {
 *                  "groups"={"get_auteur_role_adherent"}
 *              }
 *      },
 *      "post"={
 *          "method"="POST",
 *          "path"="/nationalites",
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas les droits d'acceder à cette ressource",
 *          "denormalization_context"= 
 *          {
 *                  "groups"={"put_manager"}
 *          }
 *      }
 *  },
 *   itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/nationalites/{id}",
 *              "normalization_context"= {
 *                  "groups"={"get_auteur_role_adherent"}
 *              }
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "path"="/nationalites/{id}",
 *              "security_message"="Vous n'avez pas les droits d'acceder à cette ressource",
 *              "denormalization_context"= {
 *                  "groups"={"put_role_manager"}
 *              }
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "path"="/nationalites/{id}",
 *              "security_message"="Vous n'avez pas les droits d'acceder à cette ressource",
 *          }
 * }
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "libelle": "ipartial",
 *      }
 * )
 */
class Nationalite
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
     * @Groups({"get_auteur_role_adherent"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Auteur::class, mappedBy="Nationalite")
     */
    private $auteurs;

    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Auteur[]
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteur $auteur): self
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs[] = $auteur;
            $auteur->setNationalite($this);
        }

        return $this;
    }

    public function removeAuteur(Auteur $auteur): self
    {
        if ($this->auteurs->removeElement($auteur)) {
            // set the owning side to null (unless already changed)
            if ($auteur->getNationalite() === $this) {
                $auteur->setNationalite(null);
            }
        }

        return $this;
    }
}
