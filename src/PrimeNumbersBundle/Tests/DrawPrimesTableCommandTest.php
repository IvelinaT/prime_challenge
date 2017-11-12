<?php

namespace PrimeNumbersBundle\Tests;

use PrimeNumbersBundle\Command\DrawPrimesTableCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use PrimeNumbersBundle\CMD\PrimeTableInterface;

class DrawPrimesTableCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->add(new DrawPrimesTableCommand());

        $command = $application->find(PrimeTableInterface::CMD_NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertNotEmpty($commandTester->getDisplay());
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

}