<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Symfony;

use AdminPanel\Component\DataGrid\DataGridAbstractExtension;
use AdminPanel\Symfony\AdminBundle\Datagrid\Extension\Symfony\EventSubscriber;
use AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Symfony\ColumnType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class RouterExtension extends DataGridAbstractExtension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RouterInterface $router
     * @param RequestStack $requestStack
     */
    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypes()
    {
        return [
            new ColumnType\Action($this->router, $this->requestStack),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadSubscribers()
    {
        return [
            new EventSubscriber\BindRequest(),
        ];
    }
}
