<?php

declare(strict_types=1);

namespace Setono\PostNord\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Safe\Exceptions\StringsException;
use function Safe\sprintf;

final class RequestFailedException extends RuntimeException
{
    private $request;
    private $response;
    private $statusCode;

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param int               $statusCode
     *
     * @throws StringsException
     */
    public function __construct(RequestInterface $request, ResponseInterface $response, int $statusCode)
    {
        $this->request = $request;
        $this->response = $response;
        $this->statusCode = $statusCode;

        parent::__construct(sprintf('Request failed with status code %d', $this->statusCode));
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
}
