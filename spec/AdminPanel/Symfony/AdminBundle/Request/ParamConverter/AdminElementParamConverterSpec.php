<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Request\ParamConverter;

use AdminPanel\Symfony\AdminBundle\Admin\Manager;
use PhpSpec\ObjectBehavior;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AdminElementParamConverterSpec extends ObjectBehavior
{
    public function let(Manager $manager)
    {
        $this->beConstructedWith($manager);
    }

    public function it_handle_only_fully_qualified_class_names(ParamConverter $configuration)
    {
        $configuration->getClass()->willReturn('AdminPanelDemoBundle:News');
        $this->supports($configuration)->shouldReturn(false);
    }

    public function it_supports_any_object_that_implements_element_interface(ParamConverter $configuration)
    {
        $configuration->getClass()->willReturn('AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('AdminPanel\Symfony\AdminBundle\Admin\Manager');
        $this->supports($configuration)->shouldReturn(false);
    }

    public function it_supports_objects_only(ParamConverter $configuration)
    {
        $configuration->getClass()->willReturn('');
        $this->supports($configuration)->shouldReturn(false);
    }
}
