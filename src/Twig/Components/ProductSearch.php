<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Repository\ProductRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class ProductSearch
{
    use DefaultActionTrait;
    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    public function getProducts(): array
    {
        dump($this->query); // Vérifiez si la requête est mise à jour

        if (empty(trim($this->query))) {
            return $this->productRepository->findBy([], ['name' => 'ASC']);
        }

        return $this->productRepository->searchByName($this->query);
    }
}
