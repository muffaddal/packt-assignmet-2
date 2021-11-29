<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PacktAPI;

class HomeController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     * @throws \Exception
     */
    public function index(Request $request)
    {

        $data = $this->checkIfValidResponseOrThrowException(
            PacktAPI::getAllBooks(
                $request->has('limit') ? $request->get('limit') : 0
            )
        );

        if (isset($data[ 'products' ]) && !empty($data[ 'products' ])) {
            return view('books')->with([
                'books' => PacktAPI::paginate($data[ 'products' ]) ?? null,
            ]);
        }

        throw new \Exception("No Products Found.");
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     * @throws \Exception
     */
    public function get($id
    ): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application {
        $book = $this->checkIfValidResponseOrThrowException(PacktAPI::getBook($id));
        $pricing = $this->checkIfValidResponseOrThrowException(PacktAPI::getBookPrice($id, env('CURRENCY', 'GBP')));

        return view('book_details')->with([
            'book'    => $book,
            'pricing' => $pricing,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function find(Request $request
    ): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application {
        $searchQuery = $request->get('search');

        return view('books')->with([
            'books'  => PacktAPI::search($searchQuery) ?? null,
            'search' => true,
        ]);
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
