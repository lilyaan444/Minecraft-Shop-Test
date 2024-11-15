<?php

namespace App\Controller;

use App\Entity\CreditCard;
use App\Form\CreditCardCollectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('credit_card_form')]
#[Route('/credit-cards')]
class CreditCardController extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public array $creditCards = [];

    #[Route('/', name: 'app_credit_card_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $creditCards = $entityManager->getRepository(CreditCard::class)->findBy(['user' => $this->getUser()]);

        $form = $this->createForm(CreditCardCollectionType::class, ['creditCards' => $this->creditCards]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $creditCards = $form->getData()['creditCards'];
            foreach ($creditCards as $creditCard) {
                $creditCard->setUser($this->getUser());
                $entityManager->persist($creditCard);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Credit cards have been saved successfully.');
            return $this->redirectToRoute('app_credit_card_index');
        }

        return $this->render('credit_card/index.html.twig', [
            'form' => $form->createView(),
            'creditCards' => $creditCards,
        ]);
    }
}