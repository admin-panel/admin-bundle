<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AdminPanel\Symfony\AdminBundle\Model\ResourceRepository;

Interface ResourceValue
{
    /**
     * @param string $key
     *
     * @return \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\Resource
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $textValue
     *
     * @return \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\Resource
     */
    public function setTextValue($textValue);

    /**
     * @return string
     */
    public function getTextValue();

    /**
     * @param mixed $dateValue
     *
     * @return \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\Resource
     */
    public function setDateValue($dateValue);

    /**
     * @return mixed
     */
    public function getDateValue();

    /**
     * @param \DateTime $datetimeValue
     *
     * @return \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\Resource
     */
    public function setDatetimeValue($datetimeValue);

    /**
     * @return \DateTime
     */
    public function getDatetimeValue();

    /**
     * @param mixed $timeValue
     *
     * @return \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\Resource
     */
    public function setTimeValue($timeValue);

    /**
     * @return mixed
     */
    public function getTimeValue();

    /**
     * @param mixed $numberValue
     *
     * @return \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\Resource
     */
    public function setNumberValue($numberValue);

    /**
     * @return mixed
     */
    public function getNumberValue();

    /**
     * @param int $integerValue
     *
     * @return \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\Resource
     */
    public function setIntegerValue($integerValue);

    /**
     * @return int
     */
    public function getIntegerValue();

    /**
     * @param boolean $boolValue
     *
     * @return \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\Resource
     */
    public function setBoolValue($boolValue);

    /**
     * @return boolean
     */
    public function getBoolValue();
}
