<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Tests\Driver\Doctrine;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use AdminPanel\Component\DataSource\DataSourceFactory;
use AdminPanel\Component\DataSource\DataSourceInterface;
use AdminPanel\Component\DataSource\Driver\DriverFactoryManager;
use AdminPanel\Component\DataSource\Tests\Fixtures\News;
use AdminPanel\Component\DataSource\Tests\Fixtures\Category;
use AdminPanel\Component\DataSource\Tests\Fixtures\Group;
use AdminPanel\Component\DataSource\Tests\Fixtures\TestManagerRegistry;
use AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\CoreExtension;
use AdminPanel\Component\DataSource\Driver\Doctrine\DoctrineFactory;
use AdminPanel\Component\DataSource\Extension\Symfony\Form\FormExtension;
use FSi\Component\DataSource\Extension\Symfony;
use FSi\Component\DataSource\Extension\Core;
use AdminPanel\Component\DataSource\Extension\Core\Ordering\OrderingExtension;
use Symfony\Component\Form;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use AdminPanel\Component\DataSource\Extension\Core\Pagination\PaginationExtension;
use AdminPanel\Component\DataSource\Tests\Fixtures\DoctrineDriverExtension;

/**
 * Tests for Doctrine driver.
 */
class DoctrineDriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AdminPanel\Component\DataSource\Tests\Fixtures\DoctrineDriverExtension
     */
    protected $testDoctrineExtension;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        if (!class_exists('Doctrine\ORM\EntityManager')) {
            $this->markTestSkipped('Doctrine needed!');
        }

        //The connection configuration.
        $dbParams = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/../../Fixtures'], true, null, null, false);
        $em = EntityManager::create($dbParams, $config);
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $classes = [
            $em->getClassMetadata('AdminPanel\Component\DataSource\Tests\Fixtures\News'),
            $em->getClassMetadata('AdminPanel\Component\DataSource\Tests\Fixtures\Category'),
            $em->getClassMetadata('AdminPanel\Component\DataSource\Tests\Fixtures\Group'),
        ];
        $tool->createSchema($classes);
        $this->load($em);
        $this->em = $em;
    }

    /**
     * General test for DataSource wtih DoctrineDriver in basic configuration.
     */
    public function testGeneral()
    {
        $datasourceFactory = $this->getDataSourceFactory();
        $datasources = [];
        $driverOptions = [
            'entity' => 'AdminPanel\Component\DataSource\Tests\Fixtures\News',
        ];

        $datasources[] = $datasourceFactory->createDataSource('doctrine', $driverOptions, 'datasource');
        $qb = $this->em->createQueryBuilder()
            ->select('n')
            ->from('AdminPanel\Component\DataSource\Tests\Fixtures\News', 'n');

        $driverOptions = [
            'qb' => $qb,
            'alias' => 'n'
        ];
        $datasources[] = $datasourceFactory->createDataSource('doctrine', $driverOptions, 'datasource2');

        foreach ($datasources as $datasource) {
            $datasource
                ->addField('title', 'text', 'in')
                ->addField('author', 'text', 'like')
                ->addField('created', 'datetime', 'between', [
                    'field' => 'create_date',
                ])
                ->addField('category', 'entity', 'eq')
                ->addField('category2', 'entity', 'isNull')
                ->addField('group', 'entity', 'memberof', [
                    'field' => 'groups',
                ])
                ->addField('tags', 'text', 'isNull', [
                    'field' => 'tags'
                ])
                ->addField('active', 'boolean', 'eq')
            ;

            $result1 = $datasource->getResult();
            $this->assertEquals(100, count($result1));
            $view1 = $datasource->createView();

            //Checking if result cache works.
            $this->assertSame($result1, $datasource->getResult());

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'author' => 'domain1.com',
                    ],
                ],
            ];
            $datasource->bindParameters($parameters);
            $result2 = $datasource->getResult();

            //Checking cache.
            $this->assertSame($result2, $datasource->getResult());

            $this->assertEquals(50, count($result2));
            $this->assertNotSame($result1, $result2);
            unset($result1);
            unset($result2);

            $this->assertEquals($parameters, $datasource->getParameters());

            $datasource->setMaxResults(20);
            $parameters = [
                $datasource->getName() => [
                    PaginationExtension::PARAMETER_PAGE => 1,
                ],
            ];

            $datasource->bindParameters($parameters);
            $result = $datasource->getResult();
            $this->assertEquals(100, count($result));
            $i = 0;
            foreach ($result as $item) {
                $i++;
            }

            $this->assertEquals(20, $i);

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'author' => 'domain1.com',
                        'title' => ['title44', 'title58'],
                        'created' => ['from' => new \DateTime(date("Y:m:d H:i:s", 35 * 24 * 60 * 60))],
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $view = $datasource->createView();
            $result = $datasource->getResult();
            $this->assertEquals(2, count($result));

            //Checking entity fields. We assume that database was created so first category and first group have ids equal to 1.
            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'group' => 1,
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result = $datasource->getResult();
            $this->assertEquals(25, count($result));

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'category' => 1,
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result = $datasource->getResult();
            $this->assertEquals(20, count($result));

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'group' => 1,
                        'category' => 1,
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result = $datasource->getResult();
            $this->assertEquals(5, count($result));

            //Checking sorting.
            $parameters = [
                $datasource->getName() => [
                    OrderingExtension::PARAMETER_SORT => [
                        'title' => 'asc'
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            foreach ($datasource->getResult() as $news) {
                $this->assertEquals('title0', $news->getTitle());
                break;
            }

            //Checking sorting.
            $parameters = [
                $datasource->getName() => [
                    OrderingExtension::PARAMETER_SORT => [
                        'title' => 'desc',
                        'author' => 'asc'
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            foreach ($datasource->getResult() as $news) {
                $this->assertEquals('title99', $news->getTitle());
                break;
            }

            //checking isnull & notnull
            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'tags' => 'null'
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result1 = $datasource->getResult();
            $this->assertEquals(50, count($result1));
            $ids = [];

            foreach ($result1 as $item) {
                $ids[] = $item->getId();
            }

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'tags' => 'notnull'
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result2 = $datasource->getResult();
            $this->assertEquals(50, count($result2));

            foreach ($result2 as $item) {
                $this->assertTrue(!in_array($item->getId(), $ids));
            }

            unset($result1);
            unset($result2);

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'category2' => 'null'
                    ],
                ],
            ];

            //checking isnull & notnull - field type entity
            $datasource->bindParameters($parameters);
            $result1 = $datasource->getResult();
            $this->assertEquals(50, count($result1));
            $ids = [];

            foreach ($result1 as $item) {
                $ids[] = $item->getId();
            }

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'category2' => 'notnull'
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result2 = $datasource->getResult();
            $this->assertEquals(50, count($result2));

            foreach ($result2 as $item) {
                $this->assertTrue(!in_array($item->getId(), $ids));
            }

            unset($result1);
            unset($result2);

            //checking - field type boolean
            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'active' => null
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result1 = $datasource->getResult();
            $this->assertEquals(100, count($result1));

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'active' => 1
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result2 = $datasource->getResult();
            $this->assertEquals(50, count($result2));
            $ids = [];

            foreach ($result2 as $item) {
                $ids[] = $item->getId();
            }

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'active' => 0
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result3 = $datasource->getResult();
            $this->assertEquals(50, count($result3));

            foreach ($result3 as $item) {
                $this->assertTrue(!in_array($item->getId(), $ids));
            }

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'active' => true
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result2 = $datasource->getResult();
            $this->assertEquals(50, count($result2));

            foreach ($result2 as $item) {
                $this->assertTrue(in_array($item->getId(), $ids));
            }

            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'active' => false
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            $result3 = $datasource->getResult();
            $this->assertEquals(50, count($result3));

            foreach ($result3 as $item) {
                $this->assertTrue(!in_array($item->getId(), $ids));
            }

            unset($result1);
            unset($result2);
            unset($result3);

            $parameters = [
                $datasource->getName() => [
                    OrderingExtension::PARAMETER_SORT => [
                        'active' => 'desc'
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            foreach ($datasource->getResult() as $news) {
                $this->assertEquals(true, $news->isActive());
                break;
            }

            $parameters = [
                $datasource->getName() => [
                    OrderingExtension::PARAMETER_SORT => [
                        'active' => 'asc'
                    ],
                ],
            ];

            $datasource->bindParameters($parameters);
            foreach ($datasource->getResult() as $news) {
                $this->assertEquals(false, $news->isActive());
                break;
            }

            //Test for clearing fields.
            $datasource->clearFields();
            $parameters = [
                $datasource->getName() => [
                    DataSourceInterface::PARAMETER_FIELDS => [
                        'author' => 'domain1.com',
                    ],
                ],
            ];

            //Since there are no fields now, we should have all of entities.
            $datasource->bindParameters($parameters);
            $result = $datasource->getResult();
            $this->assertEquals(100, count($result));
        }
    }

    /**
     * Checks DataSource wtih DoctrineDriver using more sophisticated QueryBuilder.
     */
    public function testQueryWithJoins()
    {
        $dataSourceFactory = $this->getDataSourceFactory();

        $qb = $this->em->createQueryBuilder()
            ->select('n')
            ->from('AdminPanel\Component\DataSource\Tests\Fixtures\News', 'n')
            ->join('n.category', 'c')
            ->join('n.groups', 'g')
        ;

        $driverOptions = [
            'qb' => $qb,
            'alias' => 'n'
        ];

        $datasource = $dataSourceFactory->createDataSource('doctrine', $driverOptions, 'datasource');
        $datasource->addField('author', 'text', 'like')
            ->addField('category', 'text', 'like', [
                'field' => 'c.name',
            ])
            ->addField('group', 'text', 'like', [
                'field' => 'g.name',
            ]);

        $parameters = [
            $datasource->getName() => [
                DataSourceInterface::PARAMETER_FIELDS => [
                    'group' => 'group0',
                ],
            ],
        ];

        $datasource->bindParameters($parameters);
        $this->assertEquals(25, count($datasource->getResult()));

        $parameters = [
            $datasource->getName() => [
                DataSourceInterface::PARAMETER_FIELDS => [
                    'group' => 'group',
                ],
            ],
        ];

        $datasource->bindParameters($parameters);
        $this->assertEquals(100, count($datasource->getResult()));

        $parameters = [
            $datasource->getName() => [
                DataSourceInterface::PARAMETER_FIELDS => [
                    'group' => 'group0',
                    'category' => 'category0',
                ],
            ],
        ];

        $datasource->bindParameters($parameters);
        $this->assertEquals(5, count($datasource->getResult()));
    }

    /**
     * Checks DataSource wtih DoctrineDriver using more sophisticated QueryBuilder.
     */
    public function testQueryWithAggregates()
    {
        $dataSourceFactory = $this->getDataSourceFactory();

        $qb = $this->em->createQueryBuilder()
            ->select('c', 'COUNT(n) AS newscount')
            ->from('AdminPanel\Component\DataSource\Tests\Fixtures\Category', 'c')
            ->join('c.news', 'n')
            ->groupBy('c')
        ;

        $driverOptions = [
            'qb' => $qb,
            'alias' => 'c'
        ];

        $datasource = $dataSourceFactory->createDataSource('doctrine', $driverOptions, 'datasource');
        $datasource
            ->addField('category', 'text', 'like', [
                'field' => 'c.name',
            ])
            ->addField('newscount', 'number', 'gt', [
                'field' => 'newscount',
                'auto_alias' => false,
                'clause' => 'having'
            ]);

        $parameters = [
            $datasource->getName() => [
                DataSourceInterface::PARAMETER_FIELDS => [
                    'newscount' => 3,
                ],
            ],
        ];

        $datasource->bindParameters($parameters);
        $datasource->getResult();

        $this->assertEquals(
            $this->testDoctrineExtension->getQueryBuilder()->getQuery()->getDQL(),
            'SELECT c, COUNT(n) AS newscount FROM AdminPanel\Component\DataSource\Tests\Fixtures\Category c INNER JOIN c.news n GROUP BY c HAVING newscount > :newscount'
        );

        $parameters = [
            $datasource->getName() => [
                DataSourceInterface::PARAMETER_FIELDS => [
                    'newscount' => 0,
                ],
            ],
        ];

        $datasource->bindParameters($parameters);
        $datasource->getResult();

        $this->assertEquals(
            $this->testDoctrineExtension->getQueryBuilder()->getQuery()->getDQL(),
            'SELECT c, COUNT(n) AS newscount FROM AdminPanel\Component\DataSource\Tests\Fixtures\Category c INNER JOIN c.news n GROUP BY c HAVING newscount > :newscount'
        );

        $datasource = $dataSourceFactory->createDataSource('doctrine', $driverOptions, 'datasource2');
        $datasource
            ->addField('category', 'text', 'like', [
                'field' => 'c.name',
            ])
            ->addField('newscount', 'number', 'between', [
                'field' => 'newscount',
                'auto_alias' => false,
                'clause' => 'having'
            ]);

        $parameters = [
            $datasource->getName() => [
                DataSourceInterface::PARAMETER_FIELDS => [
                    'newscount' => [0, 1],
                ],
            ],
        ];

        $datasource->bindParameters($parameters);
        $datasource->getResult();

        $this->assertEquals(
            $this->testDoctrineExtension->getQueryBuilder()->getQuery()->getDQL(),
            'SELECT c, COUNT(n) AS newscount FROM AdminPanel\Component\DataSource\Tests\Fixtures\Category c INNER JOIN c.news n GROUP BY c HAVING newscount BETWEEN :newscount_from AND :newscount_to'
        );
    }

    /**
     * Tests if 'having' value of 'clause' option works properly in 'entity' field
     */
    public function testHavingClauseInEntityField()
    {
        $dataSourceFactory = $this->getDataSourceFactory();

        $qb = $this->em->createQueryBuilder()
            ->select('n')
            ->from('AdminPanel\Component\DataSource\Tests\Fixtures\News', 'n')
            ->join('n.category', 'c')
        ;

        $driverOptions = [
            'qb' => $qb,
            'alias' => 'n'
        ];

        $datasource = $dataSourceFactory->createDataSource('doctrine', $driverOptions, 'datasource');
        $datasource
            ->addField('category', 'entity', 'in', [
                'clause' => 'having'
            ]);

        $parameters = [
            $datasource->getName() => [
                DataSourceInterface::PARAMETER_FIELDS => [
                    'category' => [2, 3],
                ],
            ],
        ];

        $datasource->bindParameters($parameters);
        $datasource->getResult();

        $this->assertEquals(
            $this->testDoctrineExtension->getQueryBuilder()->getQuery()->getDQL(),
            'SELECT n FROM AdminPanel\Component\DataSource\Tests\Fixtures\News n INNER JOIN n.category c HAVING n.category IN (:category)'
        );
    }

    /**
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function testCreateDriverWithoutEntityAndQbOptions()
    {
        $factory = $this->getDoctrineFactory();
        $factory->createDriver([]);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        unset($this->em);
    }

    /**
     * Return configured DoctrinFactory.
     *
     * @return \AdminPanel\Component\DataSource\Driver\Doctrine\DoctrineFactory.
     */
    private function getDoctrineFactory()
    {
        $this->testDoctrineExtension = new DoctrineDriverExtension();

        $extensions = [
            new CoreExtension(),
            $this->testDoctrineExtension
        ];

        return new \AdminPanel\Component\DataSource\Driver\Doctrine\DoctrineFactory(new TestManagerRegistry($this->em), $extensions);
    }

    /**
     * Return configured DataSourceFactory.
     *
     * @return \AdminPanel\Component\DataSource\DataSourceFactory
     */
    private function getDataSourceFactory()
    {
        $driverFactoryManager = new DriverFactoryManager([
            $this->getDoctrineFactory()
        ]);

        $extensions = [
            new \AdminPanel\Component\DataSource\Extension\Symfony\Core\CoreExtension(),
            new \AdminPanel\Component\DataSource\Extension\Core\Pagination\PaginationExtension(),
            new OrderingExtension()
        ];

        return new DataSourceFactory($driverFactoryManager, $extensions);
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    private function load(EntityManager $em)
    {
        //Injects 5 categories.
        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName('category'.$i);
            $em->persist($category);
            $categories[] = $category;
        }

        //Injects 4 groups.
        $groups = [];
        for ($i = 0; $i < 4; $i++) {
            $group = new Group();
            $group->setName('group'.$i);
            $em->persist($group);
            $groups[] = $group;
        }

        //Injects 100 newses.
        for ($i = 0; $i < 100; $i++) {
            $news = new News();
            $news->setTitle('title'.$i);

            //Half of entities will have different author and content.
            if ($i % 2 == 0) {
                $news->setAuthor('author'.$i.'@domain1.com');
                $news->setShortContent('Lorem ipsum.');
                $news->setContent('Content lorem ipsum.');
                $news->setTags('lorem ipsum');
                $news->setCategory2($categories[($i + 1) % 5]);
            } else {
                $news->setAuthor('author'.$i.'@domain2.com');
                $news->setShortContent('Dolor sit amet.');
                $news->setContent('Content dolor sit amet.');
                $news->setActive();
            }

            //Each entity has different date of creation and one of four hours of creation.
            $createDate = new \DateTime(date("Y:m:d H:i:s", $i * 24 * 60 * 60));
            $createTime = new \DateTime(date("H:i:s", (($i % 4) + 1) * 60 * 60));

            $news->setCreateDate($createDate);
            $news->setCreateTime($createTime);

            $news->setCategory($categories[$i % 5]);
            $news->getGroups()->add($groups[$i % 4]);

            $em->persist($news);
        }

        $em->flush();
    }
}
