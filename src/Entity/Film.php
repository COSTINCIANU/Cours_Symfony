<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $more_description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $additional_infos = null;

    #[ORM\Column]
    private ?int $solde_price = null;

    #[ORM\Column]
    private ?int $regular_price = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'films')]
    private Collection $categorises;

    #[ORM\Column(type: Types::ARRAY)]
    private array $imageUrls = [];

    #[ORM\Column(nullable: true)]
    private ?bool $isAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBestSeller = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isNewArrival = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isFeatured = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isSpecialOffer = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function __construct()
    {
        $this->categorises = new ArrayCollection();
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    public function getMoreDescription(): ?string
    {
        return $this->more_description;
    }

    public function setMoreDescription(?string $more_description): static
    {
        $this->more_description = $more_description;

        return $this;
    }

    public function getAdditionalInfos(): ?string
    {
        return $this->additional_infos;
    }

    public function setAdditionalInfos(?string $additional_infos): static
    {
        $this->additional_infos = $additional_infos;

        return $this;
    }

    public function getSoldePrice(): ?int
    {
        return $this->solde_price;
    }

    public function setSoldePrice(int $solde_price): static
    {
        $this->solde_price = $solde_price;

        return $this;
    }

    public function getRegularPrice(): ?int
    {
        return $this->regular_price;
    }

    public function setRegularPrice(int $regular_price): static
    {
        $this->regular_price = $regular_price;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategorises(): Collection
    {
        return $this->categorises;
    }

    public function addCategorise(Category $categorise): static
    {
        if (!$this->categorises->contains($categorise)) {
            $this->categorises->add($categorise);
        }

        return $this;
    }

    public function removeCategorise(Category $categorise): static
    {
        $this->categorises->removeElement($categorise);

        return $this;
    }

    public function getImageUrls(): array
    {
        return $this->imageUrls;
    }

    public function setImageUrls(array $imageUrls): static
    {
        $this->imageUrls = $imageUrls;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(?bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function isBestSeller(): ?bool
    {
        return $this->isBestSeller;
    }

    public function setIsBestSeller(?bool $isBestSeller): static
    {
        $this->isBestSeller = $isBestSeller;

        return $this;
    }

    public function isNewArrival(): ?bool
    {
        return $this->isNewArrival;
    }

    public function setIsNewArrival(?bool $isNewArrival): static
    {
        $this->isNewArrival = $isNewArrival;

        return $this;
    }

    public function isFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(?bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function isSpecialOffer(): ?bool
    {
        return $this->isSpecialOffer;
    }

    public function setIsSpecialOffer(?bool $isSpecialOffer): static
    {
        $this->isSpecialOffer = $isSpecialOffer;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
