<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Page;

use Symfony\Bundle\FrameworkBundle\Client;

class ListPage extends BasePage
{
    /**
     * @var string
     */
    private $pageName;

    /**
     * @param Client $client
     * @param string $pageName
     * @param Page $previousPage
     */
    public function __construct(Client $client, string $pageName = 'admin_users', Page $previousPage = null)
    {
        parent::__construct($client, $previousPage);

        $this->pageName = $pageName;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return '/list/' . $this->pageName;
    }

    /**
     * @return CreateFormPage
     */
    public function openAddNewElementForm() : CreateFormPage
    {
        return (new CreateFormPage($this->client, $this->pageName, $this))->open();
    }

    public function shouldHaveElementsOnTheList(int $howManyElements) : ListPage
    {
        $elements = $this->getCrawler()->filter(sprintf('#%s td span.datagrid-cell-value', $this->pageName))->count();

        \PHPUnit_Framework_Assert::assertEquals($howManyElements, $elements);

        return $this;
    }
}