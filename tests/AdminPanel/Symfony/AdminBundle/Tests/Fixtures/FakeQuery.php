<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Fixtures;

use Doctrine\ORM\AbstractQuery;

class FakeQuery extends AbstractQuery
{
    public function setMaxResults($number)
    {
    }

    public function setFirstResult($number)
    {
    }

    /**
     * Gets the SQL query that corresponds to this query object.
     * The returned SQL syntax depends on the connection driver that is used
     * by this query object at the time of this method call.
     *
     * @return string SQL query
     */
    public function getSQL()
    {
        // TODO: Implement getSQL() method.
    }

    /**
     * Executes the query and returns a the resulting Statement object.
     *
     * @return \Doctrine\DBAL\Driver\Statement The executed database statement that holds the results.
     */
    protected function _doExecute()
    {
        // TODO: Implement _doExecute() method.
    }
}