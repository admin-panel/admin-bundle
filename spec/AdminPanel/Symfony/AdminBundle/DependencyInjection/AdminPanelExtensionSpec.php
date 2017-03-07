<?php

declare (strict_types = 1);

namespace spec\AdminPanel\Symfony\AdminBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;

class AdminPanelExtensionSpec extends ObjectBehavior
{
    public function it_has_valid_alias()
    {
        $this->getAlias()->shouldBe('admin_panel');
    }
}