<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Factory\Worker;

use Doctrine\Common\Persistence\ManagerRegistry;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Admin\RequestStackAware;
use AdminPanel\Symfony\AdminBundle\Factory\Worker;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStackWorker implements Worker
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @inheritdoc
     */
    public function mount(Element $element)
    {
        if ($element instanceof RequestStackAware) {
            $element->setRequestStack($this->requestStack);
        }
    }
}
