<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends AbstractController
{
    /**
     * @Route("/change-language/{locale}", name="change_language")
     */
    public function changeLanguage($locale, Request $request): RedirectResponse
    {
        // Vérifie si la langue est supportée (facultatif, mais recommandé)
        $supportedLocales = ['en', 'fr']; // Ajoute les langues que tu veux prendre en charge
        if (!in_array($locale, $supportedLocales)) {
            throw $this->createNotFoundException("Cette langue n'est pas supportée.");
        }

        // Stocker la langue sélectionnée dans la session
        $request->getSession()->set('_locale', $locale);

        // Rediriger vers la page précédente ou la page d'accueil
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?: '/');
    }
}
