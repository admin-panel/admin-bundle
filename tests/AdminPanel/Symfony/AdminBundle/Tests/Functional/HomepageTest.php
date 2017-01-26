<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional;

use AdminPanel\Symfony\AdminBundle\Tests\Functional\Page\Homepage;

class HomepageTest extends FunctionalTestCase
{
    /**
     * @var Homepage
     */
    private $page;

    public function setUp()
    {
        parent::setUp();

        $client = self::createClient();
        $this->page = new Homepage($client);
    }

    public function test_that_homepage_is_working()
    {
        $this->page->open('GET');
        $this->page->shouldBeSuccessfull();
    }
}