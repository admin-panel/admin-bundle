<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field;

use AdminPanel\Component\DataSource\Driver\Doctrine\DoctrineFieldInterface;
use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Date as BaseDate;

/**
 * Date field.
 * @deprecated since version 1.2
 */
class Date extends BaseDate implements DoctrineFieldInterface
{
}
