<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MinecraftFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [
            'Blocks' => 'block',
            'Tools' => 'tool',
            'Weapons' => 'weapon',
            'Food' => 'food',
        ];

        foreach ($categories as $name => $type) {
            $category = new Category();
            $category->setName($name);
            $category->setMinecraftType($type);
            $manager->persist($category);
            $this->addReference('category_'.$type, $category);
        }

        $products = [
            ['Diamond Sword', 'weapon', 'diamond_sword.png', 100, 'A powerful sword made of diamond', 50],
            ['Golden Apple', 'food', 'golden_apple.png', 50, 'A magical apple that gives health regeneration', 100],
            ['Diamond Pickaxe', 'tool', 'diamond_pickaxe.png', 80, 'An efficient pickaxe for mining', 75],
            ['Grass Block', 'block', 'grass_block.png', 5, 'A basic building block', 1000],
        ];

        foreach ($products as [$name, $type, $image, $price, $description, $stock]) {
            $product = new Product();
            $product->setName($name);
            $product->setPrice($price);
            $product->setDescription($description);
            $product->setStock($stock);
            $product->setStatus('available');
            $product->setMinecraftImage($image);
            $product->setCategory($this->getReference('category_'.$type));
            $manager->persist($product);
        }

        $manager->flush();
    }
    
}