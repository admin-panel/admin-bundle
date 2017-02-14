<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Symfony\EventSubscriber;

use AdminPanel\Component\DataGrid\DataGridEventInterface;
use AdminPanel\Component\DataGrid\DataGridEvents;
use AdminPanel\Component\DataGrid\Exception\DataGridException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BindRequest implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [DataGridEvents::PRE_BIND_DATA => ['preBindData', 128]];
    }

    /**
     * {@inheritdoc}
     */
    public function preBindData(DataGridEventInterface $event)
    {
        $dataGrid = $event->getDataGrid();
        $request = $event->getData();

        if (!$request instanceof Request) {
            return;
        }

        $name = $dataGrid->getName();

        $default = [];

        switch ($request->getMethod()) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
            case 'PATCH':
                $data = $request->request->get($name, $default);
                break;
            case 'GET':
                $data = $request->query->get($name, $default);
                break;

            default:
                throw new DataGridException(sprintf(
                    'The request method "%s" is not supported',
                    $request->getMethod()
                ));
        }

        $event->setData($data);
    }
}
