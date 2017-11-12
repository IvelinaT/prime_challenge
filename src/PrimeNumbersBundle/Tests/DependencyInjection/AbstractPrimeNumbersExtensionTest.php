<?php

namespace PrimeNumbersBundle\Tests\DependencyInjection;

use PrimeNumbersBundle\DependencyInjection\PrimeNumbersExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
abstract class AbstractPrimeNumbersExtensionTest extends \PHPUnit\Framework\TestCase
{
    private $extension;
    private $container;

    protected function setUp()
    {
        $this->extension = new PrimeNumbersExtension();
        $this->container = new ContainerBuilder();
        $this->container->registerExtension($this->extension);
    }
    abstract protected function loadConfiguration(ContainerBuilder $container, $resource);

    public function testWithoutConfiguration()
    {
        // An extension is only loaded in the container if a configuration is provided for it.
        // Then, we need to explicitely load it.
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

        $this->assertFalse($this->container->has('prime_numbers_test'));
    }

    public function testDisabledConfiguration()
    {
        $this->loadConfiguration($this->container, 'disabled');
        $this->container->compile();

        $this->assertFalse($this->container->has('prime_numbers_test'));
    }

    public function testEnabledConfiguration()
    {
        $this->loadConfiguration($this->container, 'enabled');
        $this->container->compile();

        $this->assertTrue($this->container->has('prime_numbers_test'));
    }
}