<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Page;

interface Page
{
    /**
     * @param string $method
     * @param array $urlParameters
     * @param array $parameters
     * @return Page
     */
    public function open(string $method = 'GET', array $urlParameters = [], array $parameters = []) : Page;

    /**
     * @return string;
     */
    public function getUrl() : string;

    /**
     * @param string $url
     * @throws \RuntimeException
     * @return Page
     */
    public function shouldBeRedirectedFrom(string $url) : Page;

    /**
     * @param string $url
     * @throws \RuntimeException
     * @return Page
     */
    public function shouldBeRedirectedTo(string $url) : Page;
}