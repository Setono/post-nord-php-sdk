<?php

declare(strict_types=1);

namespace Setono\PostNord\Exception;

use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RequestFailedExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_correct_values(): void
    {
        $uri = new Uri('https://example.com/?apikey=secret&other_param=1');
        $request = $this->prophesize(RequestInterface::class);
        $request->getUri()->willReturn($uri);
        $response = $this->prophesize(ResponseInterface::class);

        $exception = new RequestFailedException($request->reveal(), $response->reveal(), 200);

        self::assertSame($request->reveal(), $exception->getRequest());
        self::assertSame($response->reveal(), $exception->getResponse());
        self::assertSame(200, $exception->getStatusCode());
    }

    /**
     * @test
     */
    public function it_masks_api_key(): void
    {
        $uri = new Uri('https://example.com/?apikey=secret&other_param=1');
        $request = $this->prophesize(RequestInterface::class);
        $request->getUri()->willReturn($uri);
        $response = $this->prophesize(ResponseInterface::class);

        $exception = new RequestFailedException($request->reveal(), $response->reveal(), 200);

        self::assertSame(
            'Request failed with status code 200. Request URI was: https://example.com/?apikey=******&other_param=1',
            $exception->getMessage()
        );
    }
}
