<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Tests\Fixtures;

use AdminPanel\Component\DataGrid\Tests\Fixtures\EntityCategory;

class Entity
{
    private $name;

    private $author;

    private $category;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return EntityCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }
}
