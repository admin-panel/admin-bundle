<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context;

use AdminPanel\Component\DataSource\DataSource;
use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement;
use AdminPanel\Component\DataGrid\DataGrid;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListElementContextSpec extends ObjectBehavior
{
    public function let(
        ListElement $element,
        DataSource $datasource,
        DataGrid $datagrid,
        HandlerInterface $handler
    ) {
        $this->beConstructedWith([$handler]);
        $element->createDataGrid()->willReturn($datagrid);
        $element->createDataSource()->willReturn($datasource);
        $this->setElement($element);
    }

    public function it_is_context()
    {
        $this->shouldBeAnInstanceOf('AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface');
    }

    public function it_have_array_data()
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('datagrid_view');
        $this->getData()->shouldHaveKeyInArray('datasource_view');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    public function it_has_template(ListElement $element)
    {
        $element->hasOption('template_list')->willReturn(true);
        $element->getOption('template_list')->willReturn('this_is_list_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_list_template.html.twig');
    }

    public function it_handle_request_with_request_handlers(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type('AdminPanel\Symfony\AdminBundle\Event\ListEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    public function it_return_response_from_handler(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type('AdminPanel\Symfony\AdminBundle\Event\ListEvent'), $request)
            ->willReturn(new Response());

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
