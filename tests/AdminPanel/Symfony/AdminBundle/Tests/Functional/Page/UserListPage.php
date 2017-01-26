<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Page;

class UserListPage extends BasePage
{
    /**
     * @return string
     */
    public function getUrl() : string
    {
        return '/list/admin_users';
    }

    /**
     * @return NewUserPage
     */
    public function openAddNewElementForm() : NewUserPage
    {
        $page = new NewUserPage($this->client, $this);

        return $page->open('GET');
    }

    public function shouldHaveElementsOnTheList(int $howManyElements) : UserListPage
    {
        $elements = $this->getCrawler()->filter('#admin_users td span.datagrid-cell-value')->count();

        \PHPUnit_Framework_Assert::assertEquals($howManyElements, $elements);

        return $this;
    }
}