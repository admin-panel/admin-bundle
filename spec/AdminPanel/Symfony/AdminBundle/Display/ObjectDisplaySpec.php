<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Display;

use AdminPanel\Symfony\AdminBundle\Display\Property;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectDisplaySpec extends ObjectBehavior
{
    public function it_throw_exception_when_value_is_not_an_object()
    {
        $object = [];
        $this->shouldThrow(new \InvalidArgumentException("Argument used to create ObjectDisplay must be an object."))
            ->during('__construct', [$object]);
    }

    public function it_throw_exception_when_property_path_is_invalid()
    {
        $object = new \stdClass();
        $object->first_name = 'Norbert';

        $this->beConstructedWith($object);

        $this->add('firstName', 'First Name');
        $this->shouldThrow('Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException')
            ->during('createView', []);
    }

    public function it_create_display_view_for_object()
    {
        $object = new \stdClass();
        $object->first_name = 'Norbert';
        $object->roles = ['ROLE_ADMIN', 'ROLE_USER'];

        $this->beConstructedWith($object);

        $this->add('first_name', 'First Name');
        $this->add('roles');

        $this->createView()->shouldHavePropertyView(new Property\View('Norbert', 'first_name', 'First Name'));
        $this->createView()->shouldHavePropertyView(new Property\View(['ROLE_ADMIN', 'ROLE_USER'], 'roles', null));
    }

    public function it_create_display_view_with_decorated_values()
    {
        $object = new \stdClass();
        $object->date = new \DateTime();
        $this->beConstructedWith($object);
        $this->add('date', null, [new Property\Formatter\DateTime('Y-m-d')]);

        $this->createView()->shouldHavePropertyView(new Property\View($object->date->format('Y-m-d'), 'date', null));
    }

    public function getMatchers()
    {
        return [
            'havePropertyView' => function ($subject, $key) {
                return in_array($key, (array) $subject->getIterator());
            },
        ];
    }
}
