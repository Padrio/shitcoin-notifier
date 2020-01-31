<?php

namespace App\Helper;

use App\Helper\Exception\FailedToDecodeAtmList;
use App\Helper\Exception\FailedToFetchAtmListException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class Fetcher
{
    const API_ENDPOINT = 'https://shitcoins.club/atms/getAtmsWithMoney?country=de';

    /**
     * @var Client
     */
    private $client;

    public function __construct(?ClientInterface $client = null)
    {
        if ($client === null) {
            $client = new Client([
                'verify' => false,
            ]);
        }

        $this->client = $client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     * @throws FailedToDecodeAtmList
     * @throws FailedToFetchAtmListException
     */
    public function getAtmList(): array
    {
        try {
            $response = $this->client->request('GET', self::API_ENDPOINT);
        } catch (GuzzleException $e) {
            throw FailedToFetchAtmListException::fromGuzzleException(
                $e->getMessage()
            );
        }

        if ($response->getStatusCode() !== 200) {
            throw FailedToFetchAtmListException::fromFailedRequest($response->getStatusCode());
        }

        return $this->decodeResponse($response);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     * @throws FailedToDecodeAtmList
     */
    private function decodeResponse(ResponseInterface $response): array
    {
        if (strpos($response->getHeaderLine('content-type'), 'application/json') === false) {
            throw FailedToDecodeAtmList::fromFailedDecode('Invalid content type');
        }

        $decoded = json_decode($response->getBody(), true);
        if($decoded === false) {
            throw FailedToDecodeAtmList::fromFailedDecode(json_last_error_msg());
        }

        return $decoded['message'][0];
    }
}