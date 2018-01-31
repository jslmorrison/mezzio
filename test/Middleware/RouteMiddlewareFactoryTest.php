<?php
/**
 * @see       https://github.com/zendframework/zend-expressive for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Expressive\Container;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Expressive\Container\RouteMiddlewareFactory;
use Zend\Expressive\Middleware\RouteMiddleware;
use Zend\Expressive\Router\RouterInterface;

class RouteMiddlewareFactoryTest extends TestCase
{
    public function testFactoryProducesRouteMiddleware()
    {
        $router = $this->prophesize(RouterInterface::class)->reveal();
        $response = $this->prophesize(ResponseInterface::class)->reveal();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(RouterInterface::class)->willReturn($router);
        $container->get(ResponseInterface::class)->willReturn($response);

        $factory = new RouteMiddlewareFactory();

        $middleware = $factory($container->reveal());

        $this->assertInstanceOf(RouteMiddleware::class, $middleware);
        $this->assertAttributeSame($router, 'router', $middleware);
        $this->assertAttributeSame($response, 'responsePrototype', $middleware);
    }
}
