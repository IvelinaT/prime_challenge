<?php

namespace PrimeNumbersBundle\Tests;

use PrimeNumbersBundle\CMD\PrimeTableArgument;
use PrimeNumbersBundle\Services\PrimeNumbersService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PrimeNumberTestCaseTest extends WebTestCase
{

    private $first = 22;
    private $limit = 5;
    private $numbers = [23, 29, 31, 37, 41];

    protected function setUp()
    {
        static::bootKernel();

    }

    public function testPrimeNumbersServiceExists()
    {
        $this->assertNotNull(
            static::$kernel->getContainer()
                ->get('prime_numbers.prime_numbers_service')
        );
    }


    public function getArgs()
    {
        $argsMock = new PrimeTableArgument();
        $argsMock->setFirst($this->first);
        $argsMock->setLimit($this->limit);

        return $argsMock;
    }

    public function testPrimeNumbersService()
    {
        $service = new PrimeNumbersService();
        $service->setPrimeNumbersArguments($this->getArgs());
        $this->assertEquals(
            $this->numbers,
            $service->getPrimeNumbers()
        );
        $this->assertNotEquals(
            [5, 7, 9, 11, 13, 15, 17],
            $service->getPrimeNumbers()
        );
        foreach ($this->numbers as $number) {
            $this->assertEquals(
                gmp_intval(gmp_nextprime($number)),
                $service->getNextPrimeNumber($number)
            );
        }
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

}