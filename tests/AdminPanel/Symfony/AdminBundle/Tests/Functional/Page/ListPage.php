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

    /**
     * @param int $howManyElements
     * @return ListPage
     */
    public function shouldHaveElementsOnTheList(int $howManyElements) : ListPage
    {
        $elements = $this->getCrawler()->filter(sprintf('#%s tbody tr', $this->pageName))->count();

        \PHPUnit_Framework_Assert::assertEquals($howManyElements, $elements);

        return $this;
    }

    /**
     * @param int $whichElement
     * @return ListPage
     */
    public function shouldHaveEditButtonOnElementNumber(int $whichElement) : ListPage
    {
        $elements = $this->getCrawler()->filter(sprintf('#%s tbody tr', $this->pageName))->each(function ($node, $i) use ($whichElement) {
            if (($whichElement - 1) == $i) {
                \PHPUnit_Framework_Assert::assertEquals(1, $node->filter('[title="Edit"]')->count());

                return $node->text();
            }
        });

        $elements = array_unique($elements);
        if (!$elements) {
            \PHPUnit_Framework_Assert::fail(sprintf('Element %d do not find', $whichElement));
        }
        return $this;
    }

    /**
     * @param int $whichElement
     * @return ListPage
     */
    public function shouldHaveDisplayButtonOnElementNumber(int $whichElement) : ListPage
    {
        $elements = $this->getCrawler()->filter(sprintf('#%s tbody tr', $this->pageName))->each(function ($node, $i) use ($whichElement) {
            if (($whichElement - 1) == $i) {
                \PHPUnit_Framework_Assert::assertEquals(1, $node->filter('[title="Display"]')->count());

                return $node->text();
            }
        });

        $elements = array_unique($elements);
        if (!$elements) {
            \PHPUnit_Framework_Assert::fail(sprintf('Element %d do not find', $whichElement));
        }
        return $this;
    }

    /**
     * @param int $whichElement
     * @return ListPage
     */
    public function shouldHaveCustomButtonOnElementNumber(int $whichElement) : ListPage
    {
        $elements = $this->getCrawler()->filter(sprintf('#%s tbody tr', $this->pageName))->each(function ($node, $i) use ($whichElement) {
            if (($whichElement - 1) == $i) {
                \PHPUnit_Framework_Assert::assertEquals(1, $node->filter('[title="Custom"]')->count());

                return $node->text();
            }
        });

        $elements = array_unique($elements);
        if (!$elements) {
            \PHPUnit_Framework_Assert::fail(sprintf('Element %d do not find', $whichElement));
        }
        return $this;
    }

    /**
     * @return ListPage
     */
    public function shouldHaveAddNewElementButton() : ListPage
    {
        $linkNodes = $this->getCrawler()->selectLink('Add new');
        \PHPUnit_Framework_Assert::assertGreaterThan(0, $linkNodes->count());

        return $this;
    }

    /**
     * @param int $whichElement
     * @return ListPage|EditPage
     */
    public function clickEditButtonForElementWithNumber(int $whichElement) : Page
    {
        $elements = $this->getCrawler()->filter(sprintf('#%s tbody tr', $this->pageName))->each(function ($node, $i) use ($whichElement) {
            if (($whichElement - 1) == $i) {
                $link = $node->filter('[title="Edit"]')->link();
                $this->client->click($link);

                return $node->text();
            }
        });

        $elements = array_unique($elements);
        if (!$elements) {
            \PHPUnit_Framework_Assert::fail(sprintf('Element %d do not find', $whichElement));

            return $this;
        }

        return new EditPage($this->client, $this->pageName, $this);
    }

    /**
     * @param int $whichElement
     * @return ListPage|CustomActionPage
     */
    public function clickCustomButtonForElementWithNumber(int $whichElement) : Page
    {
        $elements = $this->getCrawler()->filter(sprintf('#%s tbody tr', $this->pageName))->each(function ($node, $i) use ($whichElement) {
            if (($whichElement - 1) == $i) {
                $link = $node->filter('[title="Custom"]')->link();
                $this->client->click($link);

                return $node->text();
            }
        });

        $elements = array_unique($elements);
        if (!$elements) {
            \PHPUnit_Framework_Assert::fail(sprintf('Element %d do not find', $whichElement));

            return $this;
        }

        return new CustomActionPage($this->client, $this);
    }
}