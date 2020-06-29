<?php

/**
 * PSR-15 Bridge (https://github.com/kuyoto/psr15-bridge).
 *
 * PHP version 7
 *
 * @category  Library
 *
 * @author    Tolulope Kuyoro <nifskid1999@gmail.com>
 * @copyright 2020 Tolulope Kuyoro <nifskid1999@gmail.com>
 * @license   https://github.com/kuyoto/psr15-bridge/blob/master/LICENSE.md (MIT License)
 *
 * @version   GIT: master
 */

declare(strict_types=1);

namespace Kuyoto\Middleware;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * DoublePassMiddlewareDecoratorTest
 *
 * @category Library
 *
 * @author   Tolulope Kuyoro <nifskid1999@gmail.com>
 * @license  https://github.com/kuyoto/psr15-bridge/blob/master/LICENSE.md (MIT License)
 */
class DoublePassMiddlewareDecoratorTest extends TestCase
{
    /**
     * DoublePassMiddlewareDecoratorTest::testMiddlewareDelegatesToHandler()
     */
    public function testCallableMiddlewareDelegatesToHandler(): void
    {
        $middleware = function ($request, $response, callable $next) {
            return $next($request, $response);
        };

        $request = $this->prophesize(ServerRequestInterface::class);
        $response = $this->prophesize(ResponseInterface::class);
        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler->handle(Argument::that([$request, 'reveal']))
            ->will([$response, 'reveal']);

        $decorator = new DoublePassMiddlewareDecorator($middleware, $response->reveal());

        $this->assertSame($response->reveal(), $decorator->process($request->reveal(), $handler->reveal()));
    }

    /**
     * DoublePassMiddlewareDecoratorTest::testCallableMiddlewareReturnsResponse()
     */
    public function testCallableMiddlewareReturnsResponse(): void
    {
        $middleware = function ($request, $response, callable $next) {
            return $response;
        };

        $request = $this->prophesize(ServerRequestInterface::class);
        $response = $this->prophesize(ResponseInterface::class);
        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler->handle(Argument::that([$request, 'reveal']))
            ->will([$response, 'reveal']);

        $decorator = new DoublePassMiddlewareDecorator($middleware, $response->reveal());

        $this->assertSame($response->reveal(), $decorator->process($request->reveal(), $handler->reveal()));
    }

    /**
     * DoublePassMiddlewareDecoratorTest::testCallableMiddlewareThrowsExceptionWhenAResponseIsNotReturned()
     */
    public function testCallableMiddlewareThrowsExceptionWhenAResponseIsNotReturned(): void
    {
        $this->expectException(\RuntimeException::class);

        $middleware = function ($request, $response, callable $next) {
            return 'foo';
        };

        $request = $this->prophesize(ServerRequestInterface::class);
        $response = $this->prophesize(ResponseInterface::class);
        $handler = $this->prophesize(RequestHandlerInterface::class);

        $decorator = new DoublePassMiddlewareDecorator($middleware, $response->reveal());
        $decorator->process($request->reveal(), $handler->reveal());
    }
}
