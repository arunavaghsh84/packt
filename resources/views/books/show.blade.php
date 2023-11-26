@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mb-3">
                    <img src="{{ $book->image ? '/storage/images/' . $book->image : 'https://picsum.photos/1295/310' }}"
                        width="1295" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text">{{ $book->description }}</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            Author: {{ $book->author->name }}
                        </li>
                        <li class="list-group-item">
                            Category: {{ $book->category->name }}
                        </li>
                        <li class="list-group-item">
                            Publisher: {{ $book->publisher?->name ?? '---' }}
                        </li>
                        <li class="list-group-item">
                            Published: {{ $book->published ?? '---' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
