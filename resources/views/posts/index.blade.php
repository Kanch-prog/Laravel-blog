@extends('layout')

@section('content')
<div class="mt-5 container">
    <h1>Explore Posts</h1>

    <!-- Display Search Results -->
    @if ($posts->count() > 0)
        <div class="row mt-3">
            @foreach($posts as $index => $post)
                <div class="col-md-4 mt-2">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title" style="font-weight: bold;">{{ $post->title }}</h5>
                            @if ($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" width="100%" height="auto" alt="Post Image" class="img-fluid">
                            @endif
                            <p class="card-text">{{ Str::limit($post->content, 150) }}</p>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-secondary">Read More</a>
                        </div>
                    </div>
                </div>
                @if (($index + 1) % 3 == 0)
                    <div class="w-100"></div>
                @endif
            @endforeach
        </div>
    @else
        <p>No results found.</p>
    @endif
</div>
@endsection
