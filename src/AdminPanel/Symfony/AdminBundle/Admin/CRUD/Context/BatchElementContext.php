<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\ContextAbstract;
use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class BatchElementContext extends ContextAbstract
{
    /**
     * @var FormElement
     */
    protected $element;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var array
     */
    protected $indexes;

    /**
     * @param HandlerInterface[]|array $requestHandlers
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     */
    public function __construct(
        array $requestHandlers,
        FormBuilderInterface $formBuilder
    ) {
        parent::__construct($requestHandlers);

        $this->form = $formBuilder->getForm();
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = [
            'element' => $this->element,
            'indexes' => $this->indexes,
            'form' => $this->form->createView()
        ];

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setElement(Element $element)
    {
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    protected function createEvent(Request $request)
    {
        $this->indexes = $request->request->get('indexes', []);

        return new FormEvent($this->element, $request, $this->form);
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'admin_panel_batch';
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsElement(Element $element)
    {
        return $element instanceof BatchElement;
    }
}
