<?php

namespace App\Http\Controllers;

use App\Services\BookService;

class HomeController extends Controller
{
    public function __construct(protected BookService $bookService)
    {
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        ['authors' => $authors, 'categories' => $categories, 'publishers' => $publishers] = $this->bookService->getConfig();

        return view('home', compact('authors', 'categories', 'publishers'));
    }
}
