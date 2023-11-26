<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;

class BookService
{
    public function getConfig()
    {
        $authors = Author::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $publishers = Publisher::orderBy('name')->get(['id', 'name']);

        return compact('authors', 'categories', 'publishers');
    }

    public function getPublishedBooks($search, $authorId, $categoryId, $publisherId)
    {
        return Book::search($search)
            ->query(
                function ($query) use ($authorId, $categoryId, $publisherId) {
                    $this->getFilter($query, $authorId, $categoryId, $publisherId)
                        ->with([
                            'author' => fn ($query) => $query->withTrashed(),
                            'category' => fn ($query) => $query->withTrashed(),
                            'publisher' => fn ($query) => $query->withTrashed()
                        ])->whereNotNull('published');
                }
            )
            ->paginate(30);
    }

    public function getAllBooks($search, $authorId, $categoryId, $publisherId)
    {
        return Book::with([
            'author' => fn ($query) => $query->withTrashed(),
            'category' => fn ($query) => $query->withTrashed(),
            'publisher' => fn ($query) => $query->withTrashed()
        ])
            ->when($search, fn ($query) => $query->where('title', 'like', "%$search%"))
            ->where(function ($query) use ($authorId, $categoryId, $publisherId) {
                $this->getFilter($query, $authorId, $categoryId, $publisherId);
            })
            ->paginate(10);
    }

    private function getFilter(&$query, $authorId, $categoryId, $publisherId)
    {
        return $query
            ->when($authorId, fn ($query) => $query->whereAuthorId($authorId))
            ->when($categoryId, fn ($query) => $query->whereCategoryId($categoryId))
            ->when($publisherId, fn ($query) => $query->wherePublisherId($publisherId))
            ->orderByDesc('created_at');
    }
}
