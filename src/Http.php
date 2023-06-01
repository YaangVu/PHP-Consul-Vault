<?php
/**
 * @Author yaangvu
 * @Date   Apr 06, 2023
 */

namespace YaangVu\PhpConsulVault;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Http
{
    public Client   $client;
    private array   $headers   = ['Content-Type' => 'application/json'];
    private ?string $namespace = null;

    public function __construct(string $url, private readonly string $version = 'v1')
    {
        $this->setClient(new Client(['base_uri' => "$url"]));
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     *
     * @return Http
     */
    public function setClient(Client $client): Http
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param string|null $namespace
     *
     * @return Http
     */
    public function setNamespace(?string $namespace): Http
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return Http
     */
    public function setHeaders(array $headers): Http
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return static
     */
    public function headers(array $headers = []): static
    {
        return $this->setHeaders(array_merge($this->getHeaders(), $headers));
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $uri, array $params = []): mixed
    {
        $options = [
            'query'   => $params,
            'headers' => $this->getHeaders()
        ];

        return $this->request('GET', $uri, $options);
    }

    /**
     * @throws GuzzleException
     */
    public function post(string $uri, array $payload = [])
    {
        $options = [
            'json'    => $payload,
            'headers' => $this->getHeaders()
        ];

        return $this->request('POST', $uri, $options);
    }

    /**
     * @throws GuzzleException
     */
    public function put(string $uri, array $payload = [])
    {
        $options = [
            'json'    => $payload,
            'headers' => $this->getHeaders()
        ];

        return $this->request('PUT', $uri, $options);
    }

    /**
     * @throws GuzzleException
     */
    public function patch(string $uri, array $payload = [])
    {
        $options = [
            'json'    => $payload,
            'headers' => $this->getHeaders()
        ];

        return $this->request('PATCH', $uri, $options);
    }


    /**
     * @throws GuzzleException
     */
    public function request(string $method, string $uri, array $options = []): object
    {
        // Trim 'slash /' character if it appears at the first of $uri
        $uri = ltrim($uri, '/');

        $response = $this->getClient()->request($method, "$this->version/$uri", [...$options, 'debug' => false]);

        return json_decode($response->getBody()->getContents());
    }
}