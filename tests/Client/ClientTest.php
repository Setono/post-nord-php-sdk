<?php

declare(strict_types=1);

namespace Setono\PostNord\Client;

use Buzz\Client\Curl;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestInterface;
use function Safe\json_encode;

final class ClientTest extends TestCase
{
    /** @var Psr17Factory|null */
    private $psr17Factory;

    /**
     * @test
     */
    public function it_gets(): void
    {
        $response = $this->getPsr17Factory()->createResponse(200)->withBody(Stream::create(json_encode([
            'servicePoints' => [
                'test', 'test2',
            ],
        ])));
        $httpClient = $this->prophesize(HttpClientInterface::class);
        $httpClient->sendRequest(Argument::type(RequestInterface::class))->willReturn($response);

        $client = new Client($httpClient->reveal(), $this->getPsr17Factory(), $this->getPsr17Factory(), 'secret');
        $servicePoints = $client->get('/rest/businesslocation/v1/servicepoint/findNearestByAddress.json');

        self::assertIsArray($servicePoints);
    }

    /**
     * @dataProvider getValidLocations
     * @test
     */
    public function it_returns_pickup_points(string $countryCode, string $streetName, string $postalCode): void
    {
        $apiKey = getenv('POST_NORD_API_KEY');
        if (!is_string($apiKey) || '' === $apiKey) {
            self::markTestSkipped('The Post Nord API key is not present, so we skip this test');
        }

        $httpClient = new Curl($this->getPsr17Factory());
        $client = new Client($httpClient, $this->getPsr17Factory(), $this->getPsr17Factory(), $apiKey);

        $res = $client->get('/rest/businesslocation/v1/servicepoint/findNearestByAddress.json', [
            'countryCode' => $countryCode,
            'streetName' => $streetName,
            'postalCode' => $postalCode,
            'numberOfServicePoints' => 10,
        ]);

        self::assertIsArray($res);
        self::assertIsArray($res['servicePointInformationResponse']);
        self::assertIsArray($res['servicePointInformationResponse']['servicePoints']);
    }

    public function getValidLocations(): array
    {
        return [
            ['DK', 'Vesterbro 1', '9000'],
        ];
    }

    private function getPsr17Factory(): Psr17Factory
    {
        if (null === $this->psr17Factory) {
            $this->psr17Factory = new Psr17Factory();
        }

        return $this->psr17Factory;
    }
}
