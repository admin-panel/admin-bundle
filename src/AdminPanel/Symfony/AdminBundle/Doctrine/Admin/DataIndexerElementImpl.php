<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use AdminPanel\Component\DataIndexer\DoctrineDataIndexer;

trait DataIndexerElementImpl
{
    use ElementImpl;

    /**
     * @return \AdminPanel\Component\DataIndexer\DoctrineDataIndexer
     */
    public function getDataIndexer()
    {
        return new DoctrineDataIndexer($this->registry, $this->getRepository()->getClassName());
    }
}
