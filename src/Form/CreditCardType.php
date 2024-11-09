<?php

namespace App\Form;

use App\Entity\CreditCard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CreditCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'Card Number',
                'attr' => [
                    'data-controller' => 'credit-card-format',
                    'maxlength' => '19',
                    'placeholder' => '1234 5678 9012 3456'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a card number']),
                    new Length([
                        'min' => 16,
                        'max' => 16,
                        'exactMessage' => 'Card number must be exactly {{ limit }} digits'
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]{16}$/',
                        'message' => 'Card number must contain only digits'
                    ])
                ]
            ])
            ->add('expirationDate', TextType::class, [
                'label' => 'Expiration Date',
                'attr' => [
                    'data-controller' => 'expiration-date-format',
                    'maxlength' => '5',
                    'placeholder' => 'MM/YY'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter an expiration date']),
                    new Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
                        'message' => 'Invalid expiration date format (MM/YY)'
                    ])
                ]
            ])
            ->add('cvv', TextType::class, [
                'label' => 'CVV',
                'attr' => [
                    'maxlength' => '3',
                    'placeholder' => '123'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a CVV']),
                    new Length([
                        'min' => 3,
                        'max' => 3,
                        'exactMessage' => 'CVV must be exactly {{ limit }} digits'
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]{3}$/',
                        'message' => 'CVV must contain only digits'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreditCard::class,
        ]);
    }
}