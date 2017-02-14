<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement;
use AdminPanel\Symfony\AdminBundle\Event\BatchEvents;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use AdminPanel\Symfony\AdminBundle\Event\ListEvent;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use AdminPanel\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class BatchFormValidRequestHandlerSpec extends ObjectBehavior
{
    public function let(EventDispatcher $eventDispatcher, FormEvent $event, RouterInterface $router)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    public function it_is_context_request_handler()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    public function it_throw_exception_for_non_form_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormValidRequestHandler require FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    public function it_throw_exception_for_non_redirectable_element(FormEvent $formEvent, Request $request)
    {
        $formEvent->getElement()->willReturn(new \stdClass());

        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormValidRequestHandler require RedirectableElement"
            )
        )->during('handleRequest', [$formEvent, $request]);
    }

    public function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        ParameterBag $queryParameterbag,
        EventDispatcher $eventDispatcher,
        Form $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        RouterInterface $router
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

    public function it_return_redirect_response_with_redirect_uri_passed_by_request(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        ParameterBag $queryParameterbag,
        EventDispatcher $eventDispatcher,
        Form $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer
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

    public function it_return_response_from_pre_apply_event(
        FormEvent $event,
        BatchElement $element,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form
    ) {
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

    public function it_return_response_from_post_apply_event(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        EventDispatcher $eventDispatcher,
        Form $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer
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
