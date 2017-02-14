<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundleBundle\Tests\DataGrid\Extension\Symfony\EventSubscriber;

use AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Symfony\EventSubscriber\BindRequest;

class BindRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testPreBindDataWithoutRequestObject()
    {
        $event = $this->createMock('AdminPanel\Component\DataGrid\DataGridEventInterface');
        $event->expects($this->never())
            ->method('setData');

        $subscriber = new BindRequest();

        $subscriber->preBindData($event);
    }

    public function testPreBindDataPOST()
    {
        $request = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->once())
             ->method('getMethod')
             ->will($this->returnValue('POST'));

        $requestBag = $this->createMock('Symfony\Component\HttpFoundation\ParameterBag');
        $requestBag->expects($this->once())
            ->method('get')
            ->with('grid', [])
            ->will($this->returnValue(['foo' => 'bar']));

        $request->request = $requestBag;

        $grid = $this->createMock('AdminPanel\Component\DataGrid\DataGridInterface');
        $grid->expects($this->once())
             ->method('getName')
             ->will($this->returnValue('grid'));

        $event = $this->createMock('AdminPanel\Component\DataGrid\DataGridEventInterface');
        $event->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($request));

        $event->expects($this->once())
            ->method('setData')
            ->with(['foo' => 'bar']);

        $event->expects($this->once())
            ->method('getDataGrid')
            ->will($this->returnValue($grid));

        $subscriber = new BindRequest();

        $subscriber->preBindData($event);
    }

    public function testPreBindDataGET()
    {
        if (!class_exists('Symfony\Component\HttpFoundation\Request')) {
            $this->markTestSkipped('Symfony Column Extension require Symfony\Component\HttpFoundation\Request class.');
        }

        $request = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->once())
             ->method('getMethod')
             ->will($this->returnValue('GET'));

        $queryBag = $this->createMock('Symfony\Component\HttpFoundation\ParameterBag');
        $queryBag->expects($this->once())
            ->method('get')
            ->with('grid', [])
            ->will($this->returnValue(['foo' => 'bar']));

        $request->query = $queryBag;

        $grid = $this->createMock('AdminPanel\Component\DataGrid\DataGridInterface');
        $grid->expects($this->once())
             ->method('getName')
             ->will($this->returnValue('grid'));

        $event = $this->createMock('AdminPanel\Component\DataGrid\DataGridEventInterface');
        $event->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($request));

        $event->expects($this->once())
            ->method('setData')
            ->with(['foo' => 'bar']);

        $event->expects($this->once())
            ->method('getDataGrid')
            ->will($this->returnValue($grid));

        $subscriber = new BindRequest();

        $subscriber->preBindData($event);
    }
}
