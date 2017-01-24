<?php

namespace AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository;

use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValueRepository;
use AdminPanel\Symfony\AdminBundle\Repository\MapBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ResourceFormBuilder
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var \AdminPanel\Symfony\AdminBundle\Repository\MapBuilder
     */
    protected $mapBuilder;

    public function __construct(FormFactoryInterface $formFactory, MapBuilder $mapBuilder)
    {
        $this->formFactory = $formFactory;
        $this->mapBuilder = $mapBuilder;
    }

    public function build(Element $element)
    {
        $resources = $this->getResourceGroup($element->getKey());

        $builder = $this->formFactory->createBuilder(
            'form',
            $this->createFormData($element, $element->getRepository(), $resources),
            $element->getResourceFormOptions()
        );

        $this->buildForm($element, $builder, $resources);
        return $builder->getForm();
    }

    /**
     * @param string $key
     * @return array
     * @throws RuntimeException
     */
    private function getResourceGroup($key)
    {
        $map = $this->mapBuilder->getMap();
        $propertyPath = $this->createPropertyPath($key);
        $accessor = PropertyAccess::createPropertyAccessor();

        $resources = $accessor->getValue($map, $propertyPath);
        if (!is_array($resources)) {
            throw new RuntimeException(sprintf('%s its not a resource group key', $key));
        }

        return $resources;
    }

    /**
     * @param $key
     * @return string
     */
    private function createPropertyPath($key)
    {
        $parts = explode('.', $key);
        $propertyPath = '';

        foreach ($parts as $part) {
            $propertyPath .= sprintf("[%s]", $part);
        }

        return $propertyPath;
    }

    /**
     * @param Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValueRepository $valueRepository
     * @param array|\AdminPanel\Symfony\AdminBundle\Repository\Resource\Type\ResourceInterface[] $resources
     * @return array
     */
    private function createFormData(
        Element $element,
        ResourceValueRepository $valueRepository,
        array $resources
    )
    {
        $data = array();

        foreach ($resources as $resourceKey => $resource) {
            $resourceName = $this->buildResourceName($element, $resourceKey);
            $data[$this->normalizeKey($resourceName)] = $valueRepository->get($resource->getName());
        }

        return $data;
    }

    /**
     * @param Element $element
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array|\AdminPanel\Symfony\AdminBundle\Repository\Resource\Type\ResourceInterface[] $resources
     */
    private function buildForm(
        Element $element,
        FormBuilderInterface $builder,
        array $resources
    )
    {
        foreach ($resources as $resourceKey => $resource) {
            $resourceName = $this->buildResourceName($element, $resourceKey);
            $builder->add(
                $this->normalizeKey($resourceName),
                'resource',
                array(
                    'resource_key' => $resourceName,
                )
            );
        }
    }

    /**
     * @param string $key
     * @return string
     */
    private function normalizeKey($key)
    {
        return str_replace('.', '_', $key);
    }

    /**
     * @param Element $element
     * @param string $resourceKey
     * @return string
     */
    private function buildResourceName(Element $element, $resourceKey)
    {
        return sprintf("%s.%s", $element->getKey(), $resourceKey);
    }
}
