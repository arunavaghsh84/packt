@extends('layouts.app')

@section('content')
    <div class="container">
        <books :authors='@json($authors)' :categories='@json($categories)'
            :publishers='@json($publishers)'></books>
    </div>
@endsection
