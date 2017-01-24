<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Symfony\AdminBundle\Event\BatchEvents;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class BatchFormValidRequestHandlerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    public function let($eventDispatcher, $event, $router)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    public function it_is_context_request_handler()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $listEvent
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function it_throw_exception_for_non_form_event($listEvent, $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormValidRequestHandler require FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $formEvent
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function it_throw_exception_for_non_redirectable_element($formEvent, $request)
    {
        $formEvent->getElement()->willReturn(new \stdClass());

        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormValidRequestHandler require RedirectableElement"
            )
        )->during('handleRequest', [$formEvent, $request]);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestParameterbag
     * @param \Symfony\Component\HttpFoundation\ParameterBag $queryParameterbag
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \FSi\Component\DataIndexer\DataIndexerInterface $dataIndexer
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    public function it_handle_POST_request(
        $event,
        $request,
        $requestParameterbag,
        $queryParameterbag,
        $eventDispatcher,
        $form,
        $element,
        $dataIndexer,
        $router
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $request->query = $queryParameterbag;
        $requestParameterbag->get('indexes', [])->willReturn(['index']);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $element->getDataIndexer()->willReturn($dataIndexer);

        $dataIndexer->getData('index')->willReturn(new \stdClass());

        $queryParameterbag->has('redirect_uri')->willReturn(false);
        $router->generate('fsi_admin_list', ['element' => 'element_list_id'])->willReturn('/list/page');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestParameterbag
     * @param \Symfony\Component\HttpFoundation\ParameterBag $queryParameterbag
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \FSi\Component\DataIndexer\DataIndexerInterface $dataIndexer
     */
    public function it_return_redirect_response_with_redirect_uri_passed_by_request(
        $event,
        $request,
        $requestParameterbag,
        $queryParameterbag,
        $eventDispatcher,
        $form,
        $element,
        $dataIndexer
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $request->query = $queryParameterbag;
        $requestParameterbag->get('indexes', [])->willReturn(['index']);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $element->getDataIndexer()->willReturn($dataIndexer);

        $dataIndexer->getData('index')->willReturn(new \stdClass());

        $queryParameterbag->has('redirect_uri')->willReturn(true);
        $queryParameterbag->get('redirect_uri')->willReturn('some_redirect_uri');

        $response = $this->handleRequest($event, $request);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     */
    public function it_return_response_from_pre_apply_event($event, $element, $request, $eventDispatcher, $form)
    {
        $request->isMethod('POST')->willReturn(true);
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestParameterbag
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \FSi\Component\DataIndexer\DataIndexerInterface $dataIndexer
     */
    public function it_return_response_from_post_apply_event(
        $event,
        $request,
        $requestParameterbag,
        $eventDispatcher,
        $form,
        $element,
        $dataIndexer
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $requestParameterbag->get('indexes', [])->willReturn(['index']);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData('index')->willReturn(new \stdClass());

        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
