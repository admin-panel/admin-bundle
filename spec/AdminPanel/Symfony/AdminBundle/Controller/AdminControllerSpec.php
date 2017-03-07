<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;

class AdminControllerSpec extends ObjectBehavior
{
    public function let(EngineInterface $templating, RouterInterface $router)
    {
        $this->beConstructedWith($templating, $router, 'template');
    }

    public function it_stores_locale_in_session(
        Request $request,
        Session $session,
        ParameterBag $requestQuery,
        RouterInterface $router
    ) {
        $request->getSession()->willReturn($session);
        $request->query = $requestQuery;
        $router->generate('admin_panel_index')->willReturn('admin_url');

        $session->set('admin_locale', 'qw')->shouldBeCalled();

        $response = $this->localeAction('qw', $request);
        $response->getTargetUrl()->shouldReturn('admin_url');
    }

    public function it_redirects_to_passed_redirect_uri(
        Request $request,
        Session $session,
        ParameterBag $requestQuery
    ) {
        $request->getSession()->willReturn($session);
        $request->query = $requestQuery;
        $requestQuery->has('redirect_uri')->willReturn(true);
        $requestQuery->get('redirect_uri')->willReturn('uri_to_redirect_to');

        $response = $this->localeAction('qw', $request);
        $response->getTargetUrl()->shouldReturn('uri_to_redirect_to');
    }
}
