<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Twig\Extension;

use AdminPanel\Symfony\AdminBundle\Message\FlashMessages;
use PhpSpec\ObjectBehavior;

class MessageTwigExtensionSpec extends ObjectBehavior
{
    public function let(FlashMessages $flashMessages)
    {
        $this->beConstructedWith($flashMessages);
    }

    public function it_has_name()
    {
        $this->getName()->shouldReturn('admin_panel_messages');
    }

    public function it_return_all_messages(FlashMessages $flashMessages)
    {
        $flashMessages->all()->willReturn([
            'success' => [
                ['text' => 'aaa', 'domain' => 'bbb'],
                ['text' => 'ccc', 'domain' => 'ddd'],
            ],
            'error' => [
                ['text' => 'eee', 'domain' => 'fff'],
            ],
        ]);

        $this->getMessages()->shouldReturn([
            'success' => [
                ['text' => 'aaa', 'domain' => 'bbb'],
                ['text' => 'ccc', 'domain' => 'ddd'],
            ],
            'error' => [
                ['text' => 'eee', 'domain' => 'fff'],
            ],
        ]);
    }
}
