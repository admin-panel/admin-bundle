<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field;

use AdminPanel\Component\DataSource\Driver\Doctrine\DoctrineFieldInterface;
use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Time as BaseTime;

/**
 * Time field.
 * @deprecated since version 1.2
 */
class Time extends BaseTime implements DoctrineFieldInterface
{
}
