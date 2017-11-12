<?php
namespace PrimeNumbersBundle\CMD;

interface PrimeTableInterface
{
    const FIRST = 'first';
    const LIMIT = 'limit';
    const MAX_NUMBER = 100;

    const CMD_NAME = 'prime_challenge:table';
    const CMD_DESCRIPTION = 'Print out a multiplication table of prime numbers.';
}