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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Decorate psr15-bridge middleware as PSR-15 middleware.
 *
 * Decorates middleware with the following signature:
 *
 * <code>
 * function (
 *     ServerRequestInterface $request,
 *     ResponseInterface $response,
 *     callable $next
 * ) : ResponseInterface
 * </code>
 *
 * such that it will operate as PSR-15 middleware.
 *
 * Neither the arguments nor the return value need be typehinted; however, if
 * the signature is incompatible, a PHP Error will likely be thrown.
 *
 * @category Library
 *
 * @author   Tolulope Kuyoro <nifskid1999@gmail.com>
 * @license  https://github.com/kuyoto/psr15-bridge/blob/master/LICENSE.md (MIT License)
 */
final class DoublePassMiddlewareDecorator implements MiddlewareInterface
{
    /**
     * @var callable the double pass middleware
     */
    private $middleware;

    /**
     * @var ResponseInterface the response
     */
    private $response;

    /**
     * Constructor.
     *
     * @param callable          $middleware the double pass middleware
     * @param ResponseInterface $response   the response
     */
    public function __construct(callable $middleware, ResponseInterface $response)
    {
        $this->middleware = $middleware;
        $this->response = $response;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException if the decorated middleware fails to produce a response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $next = function ($request, $response) use ($handler) {
            return $handler->handle($request);
        };

        $response = ($this->middleware)($request, $this->response, $next);

        if (!$response instanceof ResponseInterface) {
            throw new \RuntimeException(sprintf(
                'Decorated callable middleware of type %s failed to produce a response.',
                is_object($this->middleware) ? get_class($this->middleware) : gettype($this->middleware)
            ));
        }

        return $response;
    }
}
