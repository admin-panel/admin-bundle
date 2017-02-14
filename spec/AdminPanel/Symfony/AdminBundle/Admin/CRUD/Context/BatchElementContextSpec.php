<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface;
use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\BatchElement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BatchElementContextSpec extends ObjectBehavior
{
    public function let(
        BatchElement $element,
        FormBuilderInterface $formBuilder,
        Form $batchForm,
        HandlerInterface $handler
    ) {
        $this->beConstructedWith([$handler], $formBuilder);
        $formBuilder->getForm()->willReturn($batchForm);
        $this->setElement($element);
    }

    public function it_is_context()
    {
        $this->shouldBeAnInstanceOf('AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface');
    }

    public function it_have_array_data()
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');
        $this->getData()->shouldHaveKeyInArray('indexes');
    }

    public function it_does_not_have_template_name()
    {
        $this->hasTemplateName()->shouldReturn(false);
        $this->getTemplateName()->shouldReturn(null);
    }

    public function it_handle_request_with_request_handlers(
        HandlerInterface $handler,
        Request $request,
        ParameterBag $requestParameterBag
    ) {
        $handler->handleRequest(Argument::type('AdminPanel\Symfony\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $request->request = $requestParameterBag;
        $requestParameterBag->get('indexes', [])->willReturn([]);

        $this->handleRequest($request)->shouldReturn(null);
    }

    public function it_return_response_from_handler(
        HandlerInterface $handler,
        Request $request,
        ParameterBag $requestParameterBag
    ) {
        $handler->handleRequest(Argument::type('AdminPanel\Symfony\AdminBundle\Event\FormEvent'), $request)
            ->willReturn(new Response());

        $request->request = $requestParameterBag;
        $requestParameterBag->get('indexes', [])->willReturn([]);

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function getMatchers()
    {
        return [
            'haveKeyInArray' => function ($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        ];
    }
}
