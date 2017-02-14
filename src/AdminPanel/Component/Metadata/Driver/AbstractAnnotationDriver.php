<?php

declare(strict_types=1);

namespace AdminPanel\Component\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;

abstract class AbstractAnnotationDriver implements DriverInterface
{
    /**
     * Annotation reader is used to actually read the annotations
     *
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * Sets annotation reader for this driver
     *
     * @param Reader $reader
     * @return AbstractAnnotationDriver
     */
    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
        return $this;
    }

    /**
     * Return previously set annotation reader or throws an exception
     *
     * @throws \RuntimeException
     * @return \Doctrine\Common\Annotations\Reader
     */
    public function getAnnotationReader()
    {
        if (!isset($this->reader)) {
            throw new \RuntimeException('Required annotation reader has not been set on the annotation driver.');
        }
        return $this->reader;
    }
}
