<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    //price
    #[ORM\Column(type: Types::FLOAT)] // Or use Types::DECIMAL if appropriate
    private ?float $price = null;

    //stock
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $stock = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    private ?string $minecraftImage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getMinecraftImage(): ?string
    {
        return $this->minecraftImage;
    }

    public function setMinecraftImage(string $minecraftImage): self
    {
        $this->minecraftImage = $minecraftImage;
        return $this;
    }

// Suggested code may be subject to a license. Learn more: ~LicenseLog:824741366.
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

// Suggested code may be subject to a license. Learn more: ~LicenseLog:808610822.
    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

// Suggested code may be subject to a license. Learn more: ~LicenseLog:

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

// Suggested code may be subject to a license. Learn more: ~LicenseLog:

    public function getStock(): ?int
    {
        return $this->stock;
    }


    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

// Suggested code may be subject to a license. Learn more: ~LicenseLog:

    public function getStatus(): ?string
    {
        return $this->status;
    }



    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }

// Suggested code may be subject to a license. Learn more: ~LicenseLog:

    public function getCategory(): ?Category
    {
        return $this->category;
    }


}




