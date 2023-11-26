<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://fakerapi.it/api/v1/books?_quantity=100');
        $result = $response->json();

        $data = $result['data'];
        $books = [];

        foreach ($data as $book) {
            $author = Author::firstOrCreate([
                'name' => $book['author'],
            ]);

            $category = Category::firstOrCreate([
                'name' => $book['genre'],
            ]);

            $publisher = Publisher::firstOrCreate([
                'name' => $book['publisher'],
            ]);

            unset($book['author']);
            unset($book['genre']);
            unset($book['publisher']);

            $book['author_id'] = $author->id;
            $book['category_id'] = $category->id;
            $book['publisher_id'] = $publisher->id;
            $book['image'] = null;

            array_push($books, $book);
        }

        Book::insert($books);
    }
}
