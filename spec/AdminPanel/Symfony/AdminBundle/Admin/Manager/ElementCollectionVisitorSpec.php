<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\Manager;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Admin\Manager;
use AdminPanel\Symfony\AdminBundle\Factory\ProductionLine;
use PhpSpec\ObjectBehavior;

class ElementCollectionVisitorSpec extends ObjectBehavior
{
    public function let(Element $adminElement, ProductionLine $productionLine)
    {
        $this->beConstructedWith([$adminElement], $productionLine);
    }

    public function it_visit_manager_and_add_into_it_elements(
        Manager $manager,
        ProductionLine $productionLine,
        Element $adminElement
    ) {
        $productionLine->workOn($adminElement)->shouldBeCalled();
        $manager->addElement($adminElement)->shouldBeCalled();

        $this->visitManager($manager);
    }
}
