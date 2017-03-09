<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends ControllerAbstract
{
    /**
     * @ParamConverter("element", class="AdminPanel\Symfony\AdminBundle\Admin\Element")
     * @param Element $element
     * @param Request $request
     * @return Response
     */
    public function listAction(Element $element, Request $request) : Response
    {
        return $this->handleRequest($element, $request, 'admin_panel_list');
    }
}
