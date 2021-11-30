<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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
        return view('books')->with([
            'books' => $this->paginate(
                    PacktAPI::getAllBooks(
                        $request->has('limit') ? $request->get('limit') : 0)
                ) ?? null,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function get($id)
    {
        return view('book_details')->with([
            'book'    => PacktAPI::getBook($id),
            'pricing' => PacktAPI::getBookPrice($id, env('CURRENCY', 'GBP')),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function find(Request $request)
    {
        return view('books')->with([
            'books'  => PacktAPI::search($request->get('search')) ?? null,
            'search' => true,
        ]);
    }

    /**
     * @param       $items
     * @param null  $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    private function paginate($items, $page = null, array $options = []): LengthAwarePaginator
    {
        $perPage = config('packt.perPage');
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
