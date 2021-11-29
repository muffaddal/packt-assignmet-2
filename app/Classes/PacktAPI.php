<?php

namespace App\Classes;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PacktAPI
{

    const PRODUCTS_ENDPOINT = '/api/v1/products';

    const BASE_URL = 'https://api.packt.com';

    /**
     * @var null
     */
    public $response = null;

    /**
     * @param int $limit
     * @return $this|string
     */
    public function getAllBooks(int $limit = 0)
    {
        try {
            $data = $this->httpClient()->request('get', self::PRODUCTS_ENDPOINT, [
                'query' => [
                    'token' => config('packt.api_key'),
                    'limit' => $limit ?: "",
                ]
            ]);

            if ($data->getStatusCode() !== 200) {
                throw new \Exception("Problem with API, Please try again.");
            }

            $this->response = json_decode($data->getBody(), true);

            return $this;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @return $this|string
     */
    public function getBook($id)
    {
        try {
            $data = $this->httpClient()->request('get', self::PRODUCTS_ENDPOINT . "/" . $id, [
                'query' => [
                    'token' => config('packt.api_key'),
                ]
            ]);

            if ($data->getStatusCode() !== 200) {
                throw new \Exception("Problem with API, Please try again.");
            }

            $this->response = json_decode($data->getBody(), true);

            return $this;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param        $id
     * @param string $currencyCode
     * @return $this|string
     */
    public function getBookPrice($id, string $currencyCode = 'GBP')
    {
        try {

            if ( !in_array($currencyCode, config('packt.accepted_currencies'))) {
                throw new \Exception("Problem with API, Unaccepted Currency");
            }

            $data = $this->httpClient()->request('get', self::PRODUCTS_ENDPOINT . "/" . $id . "/price/" . $currencyCode,
                [
                    'query' => [
                        'token' => config('packt.api_key'),
                    ]
                ]);

            if ($data->getStatusCode() !== 200) {
                throw new \Exception("Problem with API, Please try again.");
            }

            $this->response = json_decode($data->getBody(), true);

            return $this;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return Client
     */
    protected function httpClient()
    {
        return new Client([
            'base_uri' => self::BASE_URL,
        ]);
    }

    /**
     * @param       $items
     * @param null  $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function paginate($items, $page = null, array $options = []): LengthAwarePaginator
    {
        $perPage = config('packt.perPage');
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * @param $searchQuery
     * @return LengthAwarePaginator|void
     */
    public function search($searchQuery)
    {
        $data = $this->getAllBooks()->response;

        if (isset($data[ 'products' ]) && !empty($data[ 'products' ])) {
            $books = collect($data[ 'products' ]);
            $filtered = $books->filter(function ($value) use ($searchQuery) {
                return preg_match('/(' . strtolower($searchQuery) . ')/', strtolower($value[ 'title' ]), $matches,
                    PREG_OFFSET_CAPTURE);
            });

            return $this->paginate($filtered->all());
        }
    }
}
