<?php

declare(strict_types=1);

namespace Setono\PostNord\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;
use function Safe\preg_replace;
use function Safe\sprintf;

final class RequestFailedException extends RuntimeException
{
    /** @var RequestInterface */
    private $request;

    /** @var ResponseInterface */
    private $response;

    /** @var int */
    private $statusCode;

    public function __construct(RequestInterface $request, ResponseInterface $response, int $statusCode)
    {
        $this->request = $request;
        $this->response = $response;
        $this->statusCode = $statusCode;

        $uri = self::resolveUri($this->request->getUri());

        parent::__construct(sprintf('Request failed with status code %d. Request URI was: %s', $this->statusCode, $uri));
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    private static function resolveUri(UriInterface $uri): string
    {
        /** @var string $query */
        $query = preg_replace('/apikey=[^&]+/i', 'apikey=******', $uri->getQuery()); // this will mask the API key in logs etc

        return (string) $uri->withQuery($query);
    }
}
