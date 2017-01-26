<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional;

use AdminPanel\Symfony\AdminBundle\Tests\Functional\Element\UserElement;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * Returns an array of bundles to register.
     *
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[] An array of bundle instances
     */
    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \AdminPanel\Symfony\AdminBundle\AdminPanelBundle()
        ];
    }

    /**
     * Add or import routes into your application.
     *
     *     $routes->import('config/routing.yml');
     *     $routes->add('/admin', 'AppBundle:Admin:dashboard', 'admin_dashboard');
     *
     * @param RouteCollectionBuilder $routes
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->mount('/', $routes->import('@AdminPanelBundle/Resources/config/routing/admin.yml'));
    }

    /**
     * Configures the container.
     *
     * You can register extensions:
     *
     * $c->loadFromExtension('framework', array(
     *     'secret' => '%secret%'
     *  ));
     *
     * Or services:
     *
     * $c->register('halloween', 'FooBundle\HalloweenProvider');
     *
     * Or parameters:
     *
     * $c->setParameter('halloween', 'lot of fun');
     *
     * @param ContainerBuilder $c
     * @param LoaderInterface $loader
     */
    protected function configureContainer(
        ContainerBuilder $c,
        LoaderInterface $loader
    ) {
        $c->setParameter('locale', 'en');
        $c->loadFromExtension('framework', [
            'secret' => 'secret123',
            'test' => true,
            'form' => true,
            'templating' => [
                'engines' => ['twig']
            ],
            'session' => [
                'storage_id' => 'session.storage.mock_file'
            ]
        ]);
        $c->loadFromExtension('doctrine', [
            'dbal' => [
                'driver' => 'pdo_sqlite',
                'path' => '%kernel.cache_dir%/test.db',
                'charset' => 'UTF-8'
            ],
            'orm' => [
                'mappings' => [
                    'TestMapping' => [
                        'type' => 'annotation',
                        'is_bundle' => false,
                        'dir' => __DIR__ . '/Entity',
                        'prefix' => 'AdminPanel\Symfony\AdminBundle\Tests\Functional\Entity'
                    ]
                ]
            ]
        ]);

        $definition = new Definition(UserElement::class);
        $definition->addTag('admin.element');
        $c->setDefinition('admin_user_element', $definition);
    }

    /**
     * @return string
     */
    public function getCacheDir() : string
    {
        return '/dev/shm/admin-bundle/cache/' .  $this->environment;
    }

    /**
     * @return string
     */
    public function getLogDir() : string
    {
        return '/dev/shm/admin-bundle/logs';
    }
}