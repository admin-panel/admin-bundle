<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Tests\Extension\Core;

use AdminPanel\Component\DataSource\DataSource;
use AdminPanel\Component\DataSource\DataSourceFactory;
use AdminPanel\Component\DataSource\DataSourceViewInterface;
use AdminPanel\Component\DataSource\Driver\Collection\CollectionFactory;
use AdminPanel\Component\DataSource\Event\DataSourceEvent\ParametersEventArgs;
use AdminPanel\Component\DataSource\Event\DataSourceEvent\ViewEventArgs;
use AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\CoreExtension;
use AdminPanel\Component\DataSource\Driver\DriverFactoryManager;
use AdminPanel\Component\DataSource\Extension\Core\Pagination\PaginationExtension;

/**
 * Tests for Pagination Extension.
 */
class PaginationExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * First case of event (when page is not 1).
     */
    public function testPaginationExtension()
    {
        $self = $this;

        $cases = [
            [
                'first_result' => 20,
                'max_results' => 20,
                'current_page' => 2
            ],
            [
                'first_result' => 20,
                'max_results' => 0,
                'current_page' => 1
            ],
            [
                'first_result' => 0,
                'max_results' => 20,
                'current_page' => 1
            ],
        ];

        $extension = new PaginationExtension();

        foreach ($cases as $case) {
            $datasource = $this->createMock(DataSource::class);

            $datasource
                ->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('datasource'))
            ;

            $datasource
                ->expects($this->any())
                ->method('getMaxResults')
                ->will($this->returnValue($case['max_results']))
            ;

            $datasource
                ->expects($this->any())
                ->method('getFirstResult')
                ->will($this->returnValue($case['first_result']))
            ;

            $subscribers = $extension->loadSubscribers();
            $subscriber = array_shift($subscribers);
            $event = new ParametersEventArgs($datasource, []);
            $subscriber->postGetParameters($event);

            $parameters = $event->getParameters();
            if (isset($parameters['datasource'])) {
                $this->assertArrayNotHasKey(PaginationExtension::PARAMETER_PAGE, $parameters['datasource']);
            }

            $view = $this->createMock(DataSourceViewInterface::class);
            $view
                ->expects($this->any())
                ->method('setAttribute')
                ->will($this->returnCallback(function ($attribute, $value) use ($self, $case) {
                    switch ($attribute) {
                        case 'page':
                            $self->assertEquals($case['current_page'], $value);
                            break;
                    };
                }))
            ;

            $subscriber->postBuildView(new ViewEventArgs($datasource, $view));
        }
    }

    public function testSetMaxResultsByBindRequest()
    {
        $extensions = [
            new PaginationExtension()
        ];
        $driverExtensions = [new CoreExtension()];
        $driverFactory = new CollectionFactory($driverExtensions);
        $driverFactoryManager = new DriverFactoryManager([$driverFactory]);
        $factory = new DataSourceFactory($driverFactoryManager, $extensions);
        $dataSource = $factory->createDataSource('collection', [], 'foo_source');

        $dataSource->bindParameters([
            'foo_source' => [
                PaginationExtension::PARAMETER_MAX_RESULTS => 105
            ]
        ]);

        $this->assertEquals(105, $dataSource->getMaxResults());
    }
}
