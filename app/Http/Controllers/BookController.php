<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class  BookController extends Controller
{
    public function __construct(protected BookService $bookService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function publishedBooks(Request $request)
    {
        $search = $request->query('search');
        $authorId = $request->query('author_id');
        $categoryId = $request->query('category_id');
        $publisherId = $request->query('publisher_id');

        $books = $this->bookService->getPublishedBooks($search, $authorId, $categoryId, $publisherId);

        return response()->json($books);
    }

    /**
     * Display a listing of the resource.
     */
    public function allBooks(Request $request)
    {
        $search = $request->query('search');
        $authorId = $request->query('author_id');
        $categoryId = $request->query('category_id');
        $publisherId = $request->query('publisher_id');

        $books = $this->bookService->getAllBooks($search, $authorId, $categoryId, $publisherId);

        return response()->json($books);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        ['authors' => $authors, 'categories' => $categories, 'publishers' => $publishers] = $this->bookService->getConfig();

        return view('books.index', compact('authors', 'categories', 'publishers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        ['authors' => $authors, 'categories' => $categories, 'publishers' => $publishers] = $this->bookService->getConfig();

        return view('books.create', compact('authors', 'categories', 'publishers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $data = $request->validated();

        if ($request->image) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $imageName);

            $data['image'] = $imageName;
        }

        $data['isbn'] = rand(1000000000000, 9999999999999);

        Book::create($data);

        Session::flash('success', 'Book created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        ['authors' => $authors, 'categories' => $categories, 'publishers' => $publishers] = $this->bookService->getConfig();

        return view('books.edit', compact('book', 'authors', 'categories', 'publishers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Book $book, BookRequest $request)
    {
        $data = $request->validated();

        if ($request->image) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $imageName);

            $data['image'] = $imageName;
        } else {
            unset($data['image']);
        }

        $book->update($data);

        Session::flash('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        Session::flash('success', 'Book deleted successfully!');
    }
}
