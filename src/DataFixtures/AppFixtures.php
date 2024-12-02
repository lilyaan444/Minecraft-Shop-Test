<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\CreditCard;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        
        // Create Admin User
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin')); // Password: admin
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $manager->persist($admin);
        $this->addReference('user_admin', $admin);

        // Create Users
        $users = [
            ['alex.steve@gmail.com', 'Alex', 'Steve'],
            ['herobrine@gmail.com', 'Herobrine', 'Smith'],
            ['builder.jane@gmail.com', 'Jane', 'Builder'],
            ['miner.jack@gmail.com', 'Jack', 'Miner'],
            ['archer.rob@gmail.com', 'Rob', 'Archer'],
        ];

        foreach ($users as [$email, $firstName, $lastName]) {
            $user = new User();
            $user->setEmail($email);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123')); 
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $manager->persist($user);
            $this->addReference('user_' . strtolower($firstName), $user);
        }

        // Create Addresses
        $addresses = [
            ['123 Diamond St', '75001', 'Paris', 'France', 'user_alex'],
            ['999 Nether Ave', '69000', 'Lyon', 'France', 'user_herobrine'],
            ['456 Craft Blvd', '33000', 'Bordeaux', 'France', 'user_jane'],
            ['789 Mine Rd', '31000', 'Toulouse', 'France', 'user_jack'],
            ['321 Arrow Ln', '13000', 'Marseille', 'France', 'user_rob'],
        ];

        foreach ($addresses as [$street, $postalCode, $city, $country, $userReference]) {
            $address = new Address();
            $address->setStreet($street);
            $address->setPostalCode($postalCode);
            $address->setCity($city);
            $address->setCountry($country);
            $address->setUser($this->getReference($userReference));
            $manager->persist($address);
        }

        // Create Credit Cards
        $creditCards = [
            ['4000000000000001', '12/26', '123', 'user_alex'],
            ['4000000000000002', '06/25', '456', 'user_herobrine'],
            ['4000000000000003', '08/27', '789', 'user_jane'],
            ['4000000000000004', '11/24', '012', 'user_jack'],
            ['4000000000000005', '10/25', '345', 'user_rob'],
        ];

        foreach ($creditCards as [$number, $expirationDate, $cvv, $userReference]) {
            $creditCard = new CreditCard();
            $creditCard->setNumber($number);
            $creditCard->setExpirationDate($expirationDate);
            $creditCard->setCvv($cvv);
            $creditCard->setUser($this->getReference($userReference));
            $manager->persist($creditCard);
        }

        // Create Categories
        $categories = [
            ['Blocks', 'block'],
            ['Tools', 'tool'],
            ['Weapons', 'weapon'],
            ['Food', 'food'],
            ['Armor', 'armor'],
            ['Potions', 'potion'],
        ];

        foreach ($categories as [$name, $type]) {
            $category = new Category();
            $category->setName($name);
            $category->setMinecraftType($type);
            $manager->persist($category);
            $this->addReference('category_' . $type, $category);
        }

        // Create Products
        $products = [
            ['Diamond Sword', 'weapon', 'diamond_sword.png', 100, 'A powerful sword made of diamond', 50],
            ['Golden Apple', 'food', 'golden_apple.png', 50, 'A magical apple that gives health regeneration', 200],
            ['Diamond Pickaxe', 'tool', 'diamond_pickaxe.png', 80, 'An efficient pickaxe for mining', 30],
            ['Grass Block', 'block', 'grass_block.png', 5, 'A basic building block', 1000],
            ['Obsidian Block', 'block', 'obsidian_block.png', 0, 'An extremely strong block used for portals', 300],
            ['Iron Sword', 'weapon', 'iron_sword.png', 50, 'A reliable sword made of iron', 100],
            ['Bread', 'food', 'bread.png', 0, 'A basic food item', 500],
            ['Diamond Helmet', 'armor', 'diamond_helmet.png', 70, 'Protective helmet made of diamond', 40],
            ['Healing Potion', 'potion', 'healing_potion.png', 15, 'A potion that restores health', 150],
            ['Wooden Shovel', 'tool', 'wooden_shovel.png', 3, 'A basic shovel made of wood', 200],
        ];

        foreach ($products as [$name, $type, $image, $price, $description, $stock]) {
            $product = new Product();
            $product->setName($name);
            $product->setPrice($price);
            $product->setDescription($description);
            $product->setStock($stock);
            $product->setStatus('available');
            $product->setMinecraftImage($image);
            $product->setCategory($this->getReference('category_' . $type));
            $manager->persist($product);
            $this->addReference('product_' . $name, $product);
        }

        // Create Orders and OrderItems
        $orders = [
            ['ORD20231101', 'user_alex', 'completed'],
            ['ORD20231102', 'user_herobrine', 'pending'],
            ['ORD20231103', 'user_jane', 'completed'],
            ['ORD20231104', 'user_jack', 'shipped'],
            ['ORD20231105', 'user_rob', 'cancelled'],
            ['ORD20231106', 'user_alex', 'processing'],
        ];

        foreach ($orders as [$reference, $userReference, $status]) {
            $order = new Order();
            $order->setReference($reference);
            $order->setUser($this->getReference($userReference));
            $order->setStatus($status);
            $manager->persist($order);

            // Create OrderItems for each order
            $orderItems = [
                ['Diamond Sword', 1, 100],
                ['Golden Apple', 20, 5],
                ['Diamond Pickaxe', 2, 80],
                ['Wooden Shovel', 5, 3],
            ];

            foreach ($orderItems as [$productName, $quantity, $price]) {
                $orderItem = new OrderItem();
                $orderItem->setQuantity($quantity);
                $orderItem->setProductPrice($price);
                $orderItem->setOrderRef($order);
                $product = $this->getReference('product_' . $productName);
                $orderItem->setProduct($product);
                $manager->persist($orderItem);
            }
        }

        $manager->flush();
    }
}
