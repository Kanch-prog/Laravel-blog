@extends('layout')

@section('content')
<div class="mt-5">
    <h1>{!! $post->title !!}</h1>
    @if ($post->image)
        <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-fluid">
    @endif
    <p class="content">{!! $post->content !!}</p>

    <!-- Divider line -->
    <hr>

    <!-- Display existing comments -->
    @if ($post->comments)
        @if ($post->comments->count() > 0)
            <h2>Comments:</h2>
            <ul class="list-unstyled" style="margin-top: 20px;">
                @foreach ($post->comments as $comment)
                    <li style="margin-top: 20px;">
                        <strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}
                    </li>
                @endforeach
            </ul>
        @else
            <p>No comments yet.</p>
        @endif
    @endif

    <!-- Gap between post and comment section -->
    <div style="margin-top: 20px;"></div>

    <!-- Comment form -->
    <form action="{{ route('posts.addComment', $post->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="content">Add Comment:</label>
            <textarea class="form-control" id="content" name="content" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <a href="/" class="btn btn-secondary mt-3">Back to Posts</a>
</div>
@endsection
