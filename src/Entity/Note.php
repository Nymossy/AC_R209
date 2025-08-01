<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column]
    private ?int $priorite = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'notes')]
    private Collection $tags;

    /**
     * @var Collection<int, VieNote>
     */
    #[ORM\OneToMany(targetEntity: VieNote::class, mappedBy: 'note')]
    private Collection $vieNotes;

    /**
     * @var Collection<int, Galerie>
     */
    #[ORM\OneToMany(targetEntity: Galerie::class, mappedBy: 'noteLiaison')]
    private Collection $galeries;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->vieNotes = new ArrayCollection();
        $this->galeries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getPriorite(): ?int
    {
        return $this->priorite;
    }

    public function setPriorite(int $priorite): static
    {
        $this->priorite = $priorite;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tags): static
    {
        if (!$this->tags->contains($tags)) {
            $this->tags->add($tags);
        }

        return $this;
    }

    public function removeTag(Tag $tags): static
    {
        $this->tags->removeElement($tags);

        return $this;
    }

    /**
     * @return Collection<int, VieNote>
     */
    public function getVieNotes(): Collection
    {
        return $this->vieNotes;
    }

    public function addVieNote(VieNote $vieNote): static
    {
        if (!$this->vieNotes->contains($vieNote)) {
            $this->vieNotes->add($vieNote);
            $vieNote->setNote($this);
        }

        return $this;
    }

    public function removeVieNote(VieNote $vieNote): static
    {
        if ($this->vieNotes->removeElement($vieNote)) {
            // set the owning side to null (unless already changed)
            if ($vieNote->getNote() === $this) {
                $vieNote->setNote(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Galerie>
     */
    public function getGaleries(): Collection
    {
        return $this->galeries;
    }

    public function addGalery(Galerie $galery): static
    {
        if (!$this->galeries->contains($galery)) {
            $this->galeries->add($galery);
            $galery->setNoteLiaison($this);
        }

        return $this;
    }

    public function removeGalery(Galerie $galery): static
    {
        if ($this->galeries->removeElement($galery)) {
            // set the owning side to null (unless already changed)
            if ($galery->getNoteLiaison() === $this) {
                $galery->setNoteLiaison(null);
            }
        }

        return $this;
    }
}
