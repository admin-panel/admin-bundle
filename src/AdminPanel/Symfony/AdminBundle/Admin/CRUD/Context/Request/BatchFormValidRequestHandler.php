<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\AbstractFormValidRequestHandler;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement;
use AdminPanel\Symfony\AdminBundle\Event\BatchEvents;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class BatchFormValidRequestHandler extends AbstractFormValidRequestHandler
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    protected function action(FormEvent $event, Request $request)
    {
        /** @var \AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement $element */
        $element = $event->getElement();

        foreach ($request->request->get('indexes', []) as $index) {
            $element->apply($index);
        }
    }

    /**
     * @param FormEvent $event
     * @param Request $request
     * @return RedirectResponse
     */
    protected function getRedirectResponse(FormEvent $event, Request $request)
    {
        if ($request->query->has('redirect_uri')) {
            return new RedirectResponse($request->query->get('redirect_uri'));
        }

        return parent::getRedirectResponse($event, $request);
    }

    /**
     * @return string
     */
    protected function getPreSaveEventName()
    {
        return BatchEvents::BATCH_OBJECTS_PRE_APPLY;
    }

    /**
     * @return string
     */
    protected function getPostSaveEventName()
    {
        return BatchEvents::BATCH_OBJECTS_POST_APPLY;
    }
}
