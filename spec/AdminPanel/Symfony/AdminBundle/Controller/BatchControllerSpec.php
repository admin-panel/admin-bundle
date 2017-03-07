<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\BatchElementContext;
use AdminPanel\Symfony\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BatchControllerSpec extends ObjectBehavior
{
    public function let(ContextManager $manager, EngineInterface $templating)
    {
        $this->beConstructedWith(
            $templating,
            $manager
        );
    }

    public function it_dispatch_event_if_displatcher_present(
        EventDispatcherInterface $dispatcher,
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request,
        Response $response
    ) {
        $this->setEventDispatcher($dispatcher);

        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type('AdminPanel\Symfony\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $manager->createContext('admin_panel_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }

    public function it_throws_exception_when_cant_find_context_builder_that_supports_admin_element(
        BatchElement $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('batchAction', [$element, $request]);
    }

    public function it_throws_exception_when_context_does_not_return_response(
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request
    ) {
        $manager->createContext('admin_panel_batch', $element)->willReturn($context);
        $context->hasTemplateName()->willReturn(false);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow('AdminPanel\Symfony\AdminBundle\Exception\ContextException')
            ->during('batchAction', [$element, $request]);
    }

    public function it_return_response_from_context_in_batch_action(
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('admin_panel_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }
}
