<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'attr' => ['placeholder' => 'Enter product name']
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price (in diamonds)',
                'attr' => ['placeholder' => 'Enter price']
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Stock Quantity',
                'attr' => ['placeholder' => 'Enter stock quantity']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Enter product description']
            ])

            ->add('minecraftImage', TextType::class, [
                'label' => 'Minecraft Image',
                'attr' => ['placeholder' => 'Enter image filename (e.g., diamond_sword.png)']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}