<?php

declare(strict_types=1);

namespace Setono\PostNord\Client;

interface ClientInterface
{
    /**
     * Sends a GET request to the specified endpoint with the given query params.
     */
    public function get(string $endpoint, array $params = []): array;

    /**
     * Sends a POST request to the specified endpoint with the given query params and specified body.
     */
    public function post(string $endpoint, array $params = [], array $body = []): array;
}
