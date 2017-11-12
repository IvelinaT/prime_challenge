<?php
namespace PrimeNumbersBundle\CMD;


class PrimeTableArgument
{
    /**
     * @var int
     */
    private $first;

    /**
     * @var int
     */
    private $limit;

    /**
     * @return int
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @param int $first
     * @return PrimeTableArgument
     */
    public function setFirst($first)
    {
        $this->first = $first;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return PrimeTableArgument
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }


}