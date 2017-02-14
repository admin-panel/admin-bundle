<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ContextPassSpec extends ObjectBehavior
{
    public function let(ContainerBuilder $container, Definition $def)
    {
        $container->hasDefinition('admin.context.manager')->willReturn(true);
        $container->findDefinition('admin.context.manager')->willReturn($def);
    }

    public function it_add_context_builders_into_context_manager(
        ContainerBuilder $container,
        Definition $def,
        Definition $fooDef
    ) {
        $container->findTaggedServiceIds('admin.context')->willReturn([
            'builder_foo' => [[]],
        ]);

        $container->findDefinition('builder_foo')->willReturn($fooDef);
        $def->replaceArgument(0, [$fooDef])->shouldBeCalled();

        $this->process($container);
    }

    public function it_add_builders_in_priority_order(
        ContainerBuilder $container,
        Definition $def,
        Definition $fooDef,
        Definition $barDef,
        Definition $bazDef
    ) {
        $container->findTaggedServiceIds('admin.context')->willReturn([
            'builder_baz' => [['priority' => -10]],
            'builder_bar' => [[]],
            'builder_foo' => [['priority' => 5]],
        ]);

        $container->findDefinition('builder_foo')->willReturn($fooDef);
        $container->findDefinition('builder_bar')->willReturn($barDef);
        $container->findDefinition('builder_baz')->willReturn($bazDef);
        $def->replaceArgument(0, [$fooDef, $barDef, $bazDef])->shouldBeCalled();

        $this->process($container);
    }
}
