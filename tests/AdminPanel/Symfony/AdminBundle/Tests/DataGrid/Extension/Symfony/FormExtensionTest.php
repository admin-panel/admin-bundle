<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundleBundle\Tests\DataGrid\Extension\Symfony;

use AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Symfony\FormExtension;
use Symfony\Component\Form\FormFactoryInterface;

class FormExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testSymfonyFormExtension()
    {
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $extension = new FormExtension($formFactory);

        $this->assertFalse($extension->hasColumnType('foo'));
        $this->assertTrue($extension->hasColumnTypeExtensions('text'));
    }
}
