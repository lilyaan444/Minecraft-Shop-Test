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
    // ... autres propriétés ...

    #[ORM\Column(length: 255)]
    private ?string $minecraftImage = null;

    // ... autres méthodes ...

    public function getMinecraftImage(): ?string
    {
        return $this->minecraftImage;
    }

    public function setMinecraftImage(string $minecraftImage): self
    {
        $this->minecraftImage = $minecraftImage;
        return $this;
    }
}