<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    // ... autres propriétés ...

    #[ORM\Column(length: 255)]
    private ?string $minecraftType = null;

    // ... autres méthodes ...

    public function getMinecraftType(): ?string
    {
        return $this->minecraftType;
    }

    public function setMinecraftType(string $minecraftType): self
    {
        $this->minecraftType = $minecraftType;
        return $this;
    }
}