@extends('layout')

@section('content')
    <div class="container mt-4">
        <h3>Search Results for "{{ $keyword }}"</h3>
        @if ($posts->count() > 0)
            <ul>
                @foreach ($posts as $post)
                    <li>{{ $post->title }}</li>
                    <!-- Display other details of the post as needed -->
                @endforeach
            </ul>
        @else
            <p>No results found.</p>
        @endif
    </div>
@endsection
