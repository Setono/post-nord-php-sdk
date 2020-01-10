<?php

declare(strict_types=1);

namespace Setono\PostNord\Client;

use const PHP_QUERY_RFC3986;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Safe\Exceptions\JsonException;
use Safe\Exceptions\StringsException;
use function Safe\json_decode;
use function Safe\json_encode;
use Setono\PostNord\Exception\RequestFailedException;

final class Client implements ClientInterface
{
    /** @var HttpClientInterface */
    private $httpClient;

    /** @var RequestFactoryInterface */
    private $requestFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    /** @var string */
    private $apiKey;

    /** @var string */
    private $baseUrl;

    public function __construct(
        HttpClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        string $apiKey,
        string $baseUrl = 'https://api2.postnord.com'
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws JsonException
     * @throws StringsException
     */
    public function get(string $endpoint, array $params = []): array
    {
        return $this->sendRequest('GET', $endpoint, $params);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws JsonException
     * @throws StringsException
     */
    public function post(string $endpoint, array $params = [], array $body = []): array
    {
        return $this->sendRequest('POST', $endpoint, $params, $body);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws JsonException
     * @throws StringsException
     */
    private function sendRequest(string $method, string $endpoint, array $params = [], array $body = []): array
    {
        $params = array_merge([
            'apikey' => $this->apiKey,
        ], $params);

        $url = $this->baseUrl . '/' . ltrim($endpoint, '/') . '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        $request = $this->requestFactory->createRequest($method, $url);

        if (count($body) > 0) {
            $request = $request->withBody($this->streamFactory->createStream(json_encode($body)));
        }

        $response = $this->httpClient->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            throw new RequestFailedException($request, $response, $response->getStatusCode());
        }

        return (array) json_decode((string) $response->getBody(), true);
    }
}
