<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field;

use AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Exception\DoctrineDriverException;
use AdminPanel\Component\DataSource\Field\FieldAbstractType;
use Doctrine\DBAL\Query\QueryBuilder;
use AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\DoctrineField;
use Symfony\Component\Form\Extension\Core\Type\DateType;

abstract class AbstractField extends FieldAbstractType implements DoctrineField
{
    /**
     * @param QueryBuilder $queryBuilder
     * @throws DoctrineDriverException
     */
    public function buildQuery(QueryBuilder $queryBuilder)
    {
        $data = $this->getCleanParameter();
        $name = $this->getName();

        if (($data === []) || ($data === '') || ($data === null)) {
            return;
        }

        $comparison = $this->getComparison();
        $func = sprintf('and%s', ucfirst($this->getOption('clause')));

        if (in_array($comparison, ['like'], true)) {
            $data = "%$data%";
            $comparison = 'like';
        }

        if ($comparison == 'isNull') {
            $queryBuilder->$func($this->getOption('field') . ' IS ' . ($data === 'null' ? '' : 'NOT ') . 'NULL');
            return;
        }

        if ($comparison == 'between') {
            if (!is_array($data)) {
                throw new DoctrineDriverException('Fields with \'between\' comparison require to bind an array.');
            }

            $from = array_shift($data);
            $to = array_shift($data);

            if (empty($from) && ($from !== 0)) {
                $from = null;
            }

            if (empty($to) && ($to !== 0)) {
                $to = null;
            }

            if ($from === null && $to === null) {
                return;
            } elseif ($from === null) {
                $comparison = 'lte';
                $data = $this->hasDateToFormat() ? (new \DateTime($to))->format($this->getDateToFormat()) : $to;
            } elseif ($to === null) {
                $comparison = 'gte';
                $data = $this->hasDateFromFormat() ? (new \DateTime($from))->format($this->getDateFromFormat()) : $from;
            } else {
                $queryBuilder->$func($this->getOption('field') . " BETWEEN :{$name}_from AND :{$name}_to");
                $to = $this->hasDateToFormat() ? (new \DateTime($to))->format($this->getDateToFormat()) : $to;
                $from = $this->hasDateFromFormat() ? (new \DateTime($from))->format($this->getDateFromFormat()) : $from;
                $queryBuilder->setParameter("{$name}_from", $from);
                $queryBuilder->setParameter("{$name}_to", $to);
                return;
            }
        }

        $queryBuilder->$func($queryBuilder->expr()->$comparison($this->getOption('field'), ":$name"));
        $queryBuilder->setParameter($name, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $field = $this;
        $this->getOptionsResolver()
            ->setDefaults([
                'field' => null,
                'auto_alias' => true,
                'clause' => 'where'
            ])
            ->setAllowedValues('clause', ['where', 'having'])
            ->setAllowedTypes('field', ['string', 'null'])
            ->setAllowedTypes('auto_alias', 'bool')
            ->setNormalizer('field', function ($options, $value) use ($field) {
                if (!isset($value) && $field->getName()) {
                    return $field->getName();
                } else {
                    return $value;
                }
            })
            ->setNormalizer('clause', function ($options, $value) {
                return strtolower($value);
            });
    }

    /**
     * @return bool
     */
    private function hasDateFromFormat() : bool
    {
        return $this->getOption('form_from_options') && isset($this->getOption('form_from_options')['date_format']);
    }

    /**
     * @return bool
     */
    private function hasDateToFormat() : bool
    {
        return $this->getOption('form_to_options') && isset($this->getOption('form_from_options')['date_format']);
    }

    /**
     * @return string
     */
    private function getDateFromFormat() : string
    {
        if ($this->hasDateFromFormat()) {
            return $this->getOption('form_from_options')['date_format'];
        }

        throw new \RuntimeException('Date from format not exists.');
    }

    /**
     * @return string
     */
    private function getDateToFormat() : string
    {
        if ($this->hasDateToFormat()) {
            return $this->getOption('form_to_options')['date_format'];
        }

        throw new \RuntimeException('Date to format not exists.');
    }
}
