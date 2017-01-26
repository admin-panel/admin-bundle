<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional;

use AdminPanel\Symfony\AdminBundle\Tests\Functional\Page\Homepage;
use Symfony\Bundle\FrameworkBundle\Client;

class HomepageTest extends FunctionalTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    public function test_that_homepage_is_working()
    {
        (new Homepage($this->client))
            ->open()
            ->shouldSeePageTitle('Admin')
        ;
    }
}