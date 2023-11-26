@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Add Book
                        <a href="/books" class="float-end">
                            back
                        </a>
                    </div>

                    <div class="card-body">
                        <book-form :authors='@json($authors)' :categories='@json($categories)'
                            :publishers='@json($publishers)'>
                        </book-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
