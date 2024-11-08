<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $locale = $request->getSession()->get('_locale', 'en');
        $request->setLocale($locale);
    }
}
