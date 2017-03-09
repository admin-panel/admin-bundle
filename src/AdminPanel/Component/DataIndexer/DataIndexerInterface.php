<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataIndexer;

interface DataIndexerInterface
{
    /**
     * @param mixed $data
     * @return mixed
     */
    public function getIndex($data);
}
