<?php

// src/Twig/Components/ProductSearch.php
namespace App\Components;

use App\Repository\ProductRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('ProductSearch')]
class ProductSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, url: true)]
    public ?string $query = null; // Déclaration de la propriété query

    private ProductRepository $productRepo;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getProducts(): array
    {
        // Rechercher les produits par nom, ou selon la query
        return $this->productRepo->searchByName($this->query);
    }
}



