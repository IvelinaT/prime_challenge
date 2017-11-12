<?php

namespace PrimeNumbersBundle\Features\Context;

use PHPUnit\Framework\Assert as Assert;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Exception;
use Behat\MinkExtension\Context\MinkContext;
use PrimeNumbersBundle\CMD\PrimeTableArgument;
use PrimeNumbersBundle\Command\DrawPrimesTableCommand;
use LogicException;
use PrimeNumbersBundle\Services\PrimeNumbersService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Defines application features from the specific context.
 */
class PrimesContext extends MinkContext implements KernelAwareContext
{
    /** @var KernelInterface */
    private $kernel;

    /** @var Application */
    private $application;

    /** @var Command */
    private $command;

    /** @var CommandTester */
    private $commandTester;

    /** @var string */
    private $commandException;

    /** @var array */
    private $arguments;

    /** @var int */
    private $return;


    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel HttpKernel instance
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given I do not provide any parameters
     */
    public function iDoNotProvideAnyParameters()
    {
        $this->arguments = new PrimeTableArgument();
    }

    /**
     * @param TableNode $tableNode
     *
     * @Given I execute command with arguments
     */
    public function iRunTheCommandWithArgs(TableNode $tableNode)
    {
        $this->setApplication();
        $this->addCommand(new DrawPrimesTableCommand());
        $this->setCommand('prime_challenge:table');
        $this->setArguments($tableNode);

        try {
            $this->return = $this->getTester($this->command)->execute($this->arguments);
        } catch (Exception $exception) {
            $path = explode('\\', get_class($exception));
            $this->commandException = array_pop($path);
        }

    }


    /**
     * @When I execute command
     */
    public function iExecuteCommand()
    {
        $this->setApplication();
        $this->addCommand(new DrawPrimesTableCommand());
        $this->setCommand('prime_challenge:table');
        try {
            $this->return = $this->getTester($this->command)->execute(array());
        } catch (Exception $exception) {
            $path = explode('\\', get_class($exception));
            $this->commandException = array_pop($path);
        }
    }

    /**
     * @Then Command exit with success
     */
    public function theCommandExitWithSuccess()
    {
        Assert::assertEquals(0, $this->return);
    }


    /**
     * @param string $expectedException
     * @Then /^the command exception should be "([^"]*)"$/
     */
    public function theCommandExceptionShouldBe($expectedException)
    {
        if ($this->commandException != $expectedException) {
            throw new LogicException(sprintf('Current exception is: [%s]', $this->commandException));
        }

    }


    /**
     * @Then I should get table with :multiplication_count value
     *
     * @param int $multiplication_count
     */
    public function iShouldGetMultiplicationCount($multiplication_count)
    {
        $service = new PrimeNumbersService();
        $service->setPrimeNumbersArguments($this->provideCommandWithArgs());
        $primeNumbers = $service->getPrimeNumbers();

        Assert::assertEquals($multiplication_count, count($primeNumbers) * count($primeNumbers));
    }

    /**
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return $this->kernel->getContainer();
    }

    private function setApplication()
    {
        $this->application = new Application($this->kernel);
    }

    private function addCommand(Command $command)
    {
        $this->application->add($command);
    }

    private function setCommand($commandName)
    {
        $this->command = $this->application->find($commandName);
    }

    /**
     *
     * @return PrimeTableArgument
     */
    private function provideCommandWithArgs()
    {
        $serviceArguments = new PrimeTableArgument();
        $serviceArguments->setFirst($this->arguments['first']);
        $serviceArguments->setLimit($this->arguments['limit']);

        return $serviceArguments;
    }

    private function setArguments(TableNode $tableNode)
    {
        $arguments = [];
        foreach ($tableNode->getRowsHash() as $key => $value) {
            $arguments[$key] = $value;
        }

        $this->arguments = $arguments;
    }

    private function getTester(Command $command)
    {
        $this->commandTester = new CommandTester($command);

        return $this->commandTester;
    }

}
