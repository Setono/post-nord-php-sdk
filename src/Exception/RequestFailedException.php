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
    /** @var RequestInterface */
    private $request;

    /** @var ResponseInterface */
    private $response;

    /** @var int */
    private $statusCode;

    /**
     * @throws StringsException
     */
    public function __construct(RequestInterface $request, ResponseInterface $response, int $statusCode)
    {
        $this->request = $request;
        $this->response = $response;
        $this->statusCode = $statusCode;

        parent::__construct(sprintf('Request failed with status code %d. Request URI was: %s', $this->statusCode, (string) $this->request->getUri()));
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
