<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository;

use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceFormBuilderSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Repository\MapBuilder $mapBuilder
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\GenericResourceElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValueRepository $valueRepository
     * @param \AdminPanel\Symfony\AdminBundle\Repository\Resource\Type\TextType $resource
     */
    function let($mapBuilder, $formFactory, $element, $valueRepository, $resource)
    {
        $mapBuilder->getMap()->willReturn(array(
            'resources' => array(
                'resource_key' => $resource
            )
        ));
        $resource->getName()->willReturn('resources.resource_key');

        $element->getRepository()->willReturn($valueRepository);
        $element->getResourceFormOptions()->willReturn(array('form_options'));

        $this->beConstructedWith($formFactory, $mapBuilder);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\GenericResourceElement $element
     */
    function it_throw_exception_when_resource_key_is_not_resource_group_key($element)
    {
        $element->getKey()->willReturn('resources.resource_key');

        $this->shouldThrow(
            new RuntimeException("resources.resource_key its not a resource group key")
        )->during(
            'build',
            array($element)
        );
    }

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param \Symfony\Component\Form\Test\FormBuilderInterface $formBuilder
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\GenericResourceElement $element
     * @param \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValueRepository $valueRepository
     * @param \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue $resourceValue
     * @param \Symfony\Component\Form\FormInterface $form
     */
    function it_builds_form_for_resource_group(
        $formFactory,
        $formBuilder,
        $element,
        $valueRepository,
        $resourceValue,
        $form
    )
    {
        $element->getKey()->willReturn('resources');
        $valueRepository->get('resources.resource_key')->willReturn($resourceValue);

        $formFactory
            ->createBuilder('form', array('resources_resource_key' => $resourceValue), array('form_options'))
            ->willReturn($formBuilder);

        $formBuilder
            ->add('resources_resource_key', 'resource', array('resource_key' => 'resources.resource_key'))
            ->shouldBeCalled();

        $formBuilder->getForm()->willReturn($form);

        $this->build($element)->shouldReturn($form);
    }
}
