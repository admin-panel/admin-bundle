<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Page;

final class Homepage extends BasePage
{
    /**
     * @return string;
     */
    public function getUrl() : string
    {
        return '/';
    }

    public function shouldSeePageTitle(string $expectedTitle) : Homepage
    {
        $title = trim($this->getCrawler()->filter('.navbar-brand')->text());

        \PHPUnit_Framework_Assert::assertEquals($expectedTitle, $title);
        \PHPUnit_Framework_Assert::assertEquals(200, $this->client->getResponse()->getStatusCode());

        return $this;
    }
}