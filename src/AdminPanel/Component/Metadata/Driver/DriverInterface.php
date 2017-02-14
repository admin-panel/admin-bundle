<?php

declare(strict_types=1);

namespace AdminPanel\Component\Metadata\Driver;

use AdminPanel\Component\Metadata\ClassMetadataInterface;

interface DriverInterface
{
    /**
     * Load metadata into object.
     *
     * @param ClassMetadataInterface $metadata
     */
    public function loadClassMetadata(ClassMetadataInterface $metadata);
}
