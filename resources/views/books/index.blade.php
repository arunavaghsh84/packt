@extends('layouts.app')

@section('content')
    <div class="container">
        <book-list :authors='@json($authors)' :categories='@json($categories)'
            :publishers='@json($publishers)'></book-list>
    </div>
@endsection

@pushOnce('scripts')
    <script type="text/javascript"></script>
@endPushOnce
