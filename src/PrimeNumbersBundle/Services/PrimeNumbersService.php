<?php

namespace PrimeNumbersBundle\Services;


use PrimeNumbersBundle\CMD\PrimeTableArgument;

class PrimeNumbersService
{
    /**
     * @var PrimeTableArgument
     */
    private $arguments;

    /**
     * @var array
     */
    private $primeNumbers;

    public function __construct()
    {
        $this->primeNumbers = array();
    }

    /**
     * @return int
     */
    public function getNextPrimeNumber($number)
    {
        $number = $number < 0 ? 0 : $number;

        do {
            $number++;
        } while (false === $this->isPrime($number));

        return $number;
    }

    /**
     * @return bool
     */
    public function isPrime($number)
    {

        if ($number <= 1) {
            return false;
        }
        if ($number == 2) {
            return true;
        }
        if ($number % 2 == 0) {
            return false;
        }
        $ceil = ceil(sqrt($number));

        for ($i = 3; $i <= $ceil; $i = $i + 2) {
            if ($number % $i == 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getPrimeNumbers()
    {
        $number = $this->arguments->getFirst();
        $limit = $this->arguments->getLimit();
        $counter = 0;
        if ($this->isPrime($number)) {
            $this->primeNumbers[] = $number;
            $counter++;
        }
        for ($counter; $counter < $limit; $counter++) {
            $number = $this->getNextPrimeNumber($number);
            $this->primeNumbers[] = $number;
        }

        return $this->primeNumbers;
    }

    public function setPrimeNumbersArguments(PrimeTableArgument $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return PrimeTableArgument
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param PrimeTableArgument $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }
}