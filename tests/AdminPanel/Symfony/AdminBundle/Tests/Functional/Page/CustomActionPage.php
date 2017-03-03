<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Page;

class CustomActionPage extends BasePage
{
    /**
     * @return string;
     */
    public function getUrl() : string
    {
        return '/custom-action/{id}';
    }
}