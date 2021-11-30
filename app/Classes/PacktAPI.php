<?php

namespace App\Classes;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class PacktAPI
{

    const PRODUCTS_ENDPOINT = '/api/v1/products';

    const BASE_URL = 'https://api.packt.com';

    public array $queryParams = [];

    /**
     * @var null
     */
    public $response = null;

    public function __construct()
    {
        $this->setQueryParams([
            'token' => config('packt.api_key'),
        ]);
    }

    /***
     * @param int $limit
     * @return mixed
     */
    public function getAllBooks(int $limit = 0): mixed
    {
        try {

            if ($limit > 0) {
                $this->queryParams[ 'limit' ] = $limit;
            }

            if (Cache::has("books") && !$limit > 0) {
                return Cache::get("books");
            }

            $data = $this->checkIfValidResponseOrThrowException(
                $this->apiRequest(self::PRODUCTS_ENDPOINT, 'get')
            );

            if (isset($data[ 'products' ]) && !empty($data[ 'products' ])) {
                Cache::put('books', $data[ 'products' ]);

                return $data[ 'products' ];
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getBook($id): mixed
    {
        try {
            if (Cache::has($id . "-book")) {
                return Cache::get($id . "-book");
            }

            $data = $this->checkIfValidResponseOrThrowException(
                $this->apiRequest(self::PRODUCTS_ENDPOINT . "/" . $id, 'get')
            );

            Cache::put($id . "-book", $data);

            return $data;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param        $id
     * @param string $currencyCode
     * @return mixed
     */
    public function getBookPrice($id, string $currencyCode = 'GBP'): mixed
    {
        try {

            if ( !in_array($currencyCode, config('packt.accepted_currencies'))) {
                throw new \Exception("Problem with API, Unaccepted Currency");
            }

            if (Cache::has($id . "-pricing-" . $currencyCode)) {
                return Cache::get($id . "-pricing-" . $currencyCode);
            }

            $data = $this->checkIfValidResponseOrThrowException(
                $this->apiRequest(self::PRODUCTS_ENDPOINT . "/" . $id . "/price/" . $currencyCode, 'get')
            );

            Cache::put($id . "-pricing-" . $currencyCode, $data);

            return $data;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return Client
     */
    protected function httpClient(): Client
    {
        return new Client([
            'base_uri' => self::BASE_URL,
        ]);
    }

    /**
     * @param $searchQuery
     * @return array
     */
    public function search($searchQuery): array
    {
        return collect($this->getAllBooks())->filter(function ($value) use ($searchQuery) {
            return preg_match('/(' . strtolower($searchQuery) . ')/', strtolower($value[ 'title' ]), $matches,
                PREG_OFFSET_CAPTURE);
        })->all();
    }

    /**
     * @param        $url
     * @param string $method
     * @return PacktAPI
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function apiRequest($url, string $method): PacktAPI
    {
        $request = $this->httpClient()->request($method, $url, [
            'query' => $this->queryParams
        ]);

        if ($request->getStatusCode() !== 200) {
            throw new \Exception("Problem with API, Please try again.");
        }

        $this->response = json_decode($request->getBody(), true);

        return $this;
    }

    /**
     * @param $array
     * @return void
     */
    private function setQueryParams($array): void
    {
        $this->queryParams = $array;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    private function checkIfValidResponseOrThrowException($data): mixed
    {
        return is_string($data) ? throw new \Exception($data) : $data->response;
    }
}
