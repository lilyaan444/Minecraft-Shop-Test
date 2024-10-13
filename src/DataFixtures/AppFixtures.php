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
        // Create Users
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $manager->persist($user);

        // Create Addresses
        $address = new Address();
        $address->setStreet('123 Main St');
        $address->setPostalCode('12345');
        $address->setCity('Anytown');
        $address->setCountry('USA');
        $address->setUser($user);
        $manager->persist($address);

        // Create Credit Cards
        $creditCard = new CreditCard();
        $creditCard->setNumber('4111111111111111');
        $creditCard->setExpirationDate('12/25');
        $creditCard->setCvv('123');
        $creditCard->setUser($user);
        $manager->persist($creditCard);

        // Create Categories
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

        // Create Products
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

        // Create Orders and OrderItems
        $order = new Order();
        $order->setReference('ORD123456');
        $order->setUser($user);
        $order->setStatus('pending');
        $manager->persist($order);

        foreach ($products as [$name, $type, $image, $price, $description, $stock]) {
            $orderItem = new OrderItem();
            $orderItem->setQuantity(1);
            $orderItem->setProductPrice($price);
            $orderItem->setOrderRef($order);
            $product = $this->getReference('product_' . $type); // Assuming you have a reference for products
            $orderItem->setProduct($product);
            
            $manager->persist($orderItem);
        }

        

        $manager->flush();
    }
}