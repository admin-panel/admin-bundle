<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\EventListener;

use AdminPanel\Symfony\AdminBundle\EventListener\LocaleMenuListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;
use AdminPanel\Symfony\AdminBundle\Event\MenuEvent;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use Symfony\Component\HttpFoundation\Request;

class LocaleMenuListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LocaleMenuListener
     */
    private $listener;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function setUp()
    {
        $translator = $this->prophesize(TranslatorInterface::class);
        $this->requestStack = $this->prophesize(RequestStack::class);

        $this->listener = new LocaleMenuListener(
            $translator->reveal(),
            $this->requestStack->reveal(),
            ['en', 'de']
        );
    }

    public function test_that_should_build_locale_menu() {
        $event = $this->prophesize(MenuEvent::class);
        $request = $this->prophesize(Request::class);

        $menu = new Item();
        $event->getMenu()->willReturn($menu);
        $this->requestStack->getMasterRequest()->willReturn($request);
        $request->getLocale()->willReturn('de');
        $request->getUri()->willReturn('uri_to_redirect_to');

        $this->listener->createLocaleMenu($event->reveal());

        $rootItem = $menu->getChildren()['admin-locale'];
        $localeItems = $rootItem->getChildren();

        $enItem = $localeItems['admin-locale.en'];
        $deItem = $localeItems['admin-locale.de'];

        $enItem->setSafeLabel();

        $this->assertEquals($enItem->getLabel(), 'Englisch');
        $this->assertEquals($enItem->getRoute(), 'admin_panel_locale');
        $this->assertEquals($enItem->getRouteParameters(), ['_locale' => 'en', 'redirect_uri' => 'uri_to_redirect_to']);
        $this->assertEquals($enItem->getOptions(), ['attr' => ['id' => null, 'class' => null]]);
        $this->assertTrue($enItem->isSafeLabel());

        $this->assertEquals($deItem->getLabel(), 'Deutsch');
        $this->assertEquals($deItem->getRoute(), 'admin_panel_locale');
        $this->assertEquals($deItem->getRouteParameters(), ['_locale' => 'de', 'redirect_uri' => 'uri_to_redirect_to']);
        $this->assertEquals($deItem->getOptions(), ['attr' => ['id' => null, 'class' => 'active']]);
        $this->assertFalse($deItem->isSafeLabel());
    }
}