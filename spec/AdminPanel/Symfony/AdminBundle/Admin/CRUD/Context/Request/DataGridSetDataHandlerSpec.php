<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Component\DataSource\DataSource;
use AdminPanel\Component\DataSource\Exception\PageNotFoundException;
use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use AdminPanel\Symfony\AdminBundle\Event\ListEvent;
use AdminPanel\Symfony\AdminBundle\Event\ListEvents;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use AdminPanel\Component\DataGrid\DataGrid;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DataGridSetDataHandlerSpec extends ObjectBehavior
{
    public function let(EventDispatcher $eventDispatcher, ListEvent $event)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    public function it_is_context_request_handler()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    public function it_throw_exception_for_non_list_event(AdminEvent $event, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataGridSetDataHandler require ListEvent"
            )
        )->during('handleRequest', [$event, $request]);
    }

    public function it_set_data_at_datagrid_and_dispatch_events(
        ListEvent $event,
        DataSource $dataSource,
        DataGrid $dataGrid,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $dataSource->getResult()->willReturn([1]);
        $dataGrid->setData([1])->shouldBeCalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_POST_BIND, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_datagrid_pre_bind_data(
        EventDispatcher $eventDispatcher,
        ListEvent $event,
        Request $request
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_PRE_BIND, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function it_return_response_from_datagrid_post_bind_data(
        EventDispatcher $eventDispatcher,
        ListEvent $event,
        Request $request,
        DataGrid $dataGrid,
        DataSource $dataSource
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $dataSource->getResult()->willReturn([1]);
        $dataGrid->setData([1])->shouldBeCalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_POST_BIND, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function it_throws_not_found_exception_if_current_page_is_out_of_bound(
        DataSource $dataSource,
        DataGrid $dataGrid,
        ListEvent $event,
        Request $request
    ) {
        $dataSource->getResult()->willThrow(new PageNotFoundException());

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $this->shouldThrow(NotFoundHttpException::class)->during('handleRequest', [$event, $request]);
    }
}
