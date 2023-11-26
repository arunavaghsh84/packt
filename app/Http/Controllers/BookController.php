<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class  BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function publishedBooks(Request $request)
    {
        $search = $request->query('search');
        $author_id = $request->query('author_id');
        $category_id = $request->query('category_id');
        $publisher_id = $request->query('publisher_id');

        $books = Book::search($search)
            ->query(fn ($query) => $query->with(['author', 'category', 'publisher'])
                ->whereNotNull('published')
                ->when($author_id, fn ($query) => $query->whereAuthorId($author_id))
                ->when($category_id, fn ($query) => $query->whereCategoryId($category_id))
                ->when($publisher_id, fn ($query) => $query->wherePublisherId($publisher_id))
                ->orderByDesc('created_at'))
            ->paginate(30);

        return response()->json($books);
    }

    /**
     * Display a listing of the resource.
     */
    public function allBooks(Request $request)
    {
        $search = $request->query('search');
        $author_id = $request->query('author_id');
        $category_id = $request->query('category_id');
        $publisher_id = $request->query('publisher_id');

        $books = Book::with(['author', 'category', 'publisher'])
            ->when($search, fn ($query) => $query->where('title', 'like', "%$search%"))
            ->when($author_id, fn ($query) => $query->whereAuthorId($author_id))
            ->when($category_id, fn ($query) => $query->whereCategoryId($category_id))
            ->when($publisher_id, fn ($query) => $query->wherePublisherId($publisher_id))
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($books);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $publishers = Publisher::orderBy('name')->get(['id', 'name']);

        return view('books.index', compact('authors', 'categories', 'publishers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $publishers = Publisher::orderBy('name')->get(['id', 'name']);

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
        $authors = Author::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $publishers = Publisher::orderBy('name')->get(['id', 'name']);

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
