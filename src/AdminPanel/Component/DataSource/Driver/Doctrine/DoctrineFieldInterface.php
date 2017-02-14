<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine;

use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\DoctrineFieldInterface as BaseDoctrineField;

/**
 * Interface for Doctrine driver's fields.
 * @deprecated since version 1.2
 */
interface DoctrineFieldInterface extends BaseDoctrineField
{
}
