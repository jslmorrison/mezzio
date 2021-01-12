<?php

/**
 * @see       https://github.com/mezzio/mezzio for the canonical source repository
 * @copyright https://github.com/mezzio/mezzio/blob/master/COPYRIGHT.md
 * @license   https://github.com/mezzio/mezzio/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace MezzioTest\Container;

use Mezzio\Container\WhoopsErrorResponseGeneratorFactory;
use Mezzio\Middleware\WhoopsErrorResponseGenerator;
use MezzioTest\InMemoryContainer;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Whoops\Run;
use Whoops\RunInterface;

use function interface_exists;

class WhoopsErrorResponseGeneratorFactoryTest extends TestCase
{
    /** @var InMemoryContainer */
    private $container;

    /** @var Run|RunInterface|ObjectProphecy */
    private $whoops;

    public function setUp() : void
    {
        $this->container = new InMemoryContainer();

        $this->whoops = interface_exists(RunInterface::class)
            ? $this->prophesize(RunInterface::class)
            : $this->prophesize(Run::class);
    }

    public function testCreatesInstanceWithConfiguredWhoopsService() : void
    {
        $this->container->set('Mezzio\Whoops', $this->whoops->reveal());

        $factory = new WhoopsErrorResponseGeneratorFactory();

        $generator = $factory($this->container);

        $this->assertInstanceOf(WhoopsErrorResponseGenerator::class, $generator);
        $this->assertAttributeSame($this->whoops->reveal(), 'whoops', $generator);
    }
}
