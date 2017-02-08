<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class FunctionalTestCase extends WebTestCase
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var DatabaseContext
     */
    protected $dbContext;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Creates a Kernel.
     *
     * Available options:
     *
     *  * environment
     *  * debug
     *
     * @param array $options An array of options
     *
     * @return KernelInterface A KernelInterface instance
     */
    protected static function createKernel(array $options = array())
    {
        return new AppKernel(
            'test',
            true
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->manager = static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
        $this->dbContext = new DatabaseContext($this->manager);

        $this->createSchema();

        $this->client = self::createClient();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->dropSchema();
    }


    protected function createSchema()
    {
        $metadata = $this->manager->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($this->manager);
        $tool->dropSchema($metadata);
        $tool->createSchema($metadata);
    }

    protected function dropSchema()
    {
        $metadata = $this->manager->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($this->manager);
        $tool->dropSchema($metadata);
    }
}