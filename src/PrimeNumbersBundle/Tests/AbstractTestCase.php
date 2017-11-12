<?php

namespace PrimeNumbersBundle\Tests;

use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    /**
     * @param string $originalClassName
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMock($originalClassName)
    {
        if (is_callable('parent::createMock')) {
            return parent::createMock($originalClassName);
        }
        return $this->getMock($originalClassName);
    }
}