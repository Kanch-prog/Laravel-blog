<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Post;   
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function dashboard()
    {
        // Fetch posts posted by the currently logged-in user
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->get();

        return view('dashboard', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $cleanContent = $purifier->purify($validated['content']);

        $post = new Post();
        $post->title = $validated['title'];
        $post->content = $cleanContent;
        $post->user_id = auth()->id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imagePath = $file->store('images', 'public');
            $post->image = $imagePath;

            // Debugging statements
            \Log::info('Image Path: ' . $imagePath);
        }

        // Debugging statements for other validated data
        \Log::info('Validated Data: ', $validated);

        $post->save();

        return redirect()->route('index');
    }

    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $cleanContent = $purifier->purify($validated['content']);

        $post = Post::findOrFail($id);
        $post->title = $validated['title'];
        $post->content = $cleanContent;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $post->image = $imagePath;
        }

        $post->save();

        return redirect()->route('dashboard')->with('success', 'Post updated successfully');
    }


    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();

        return redirect()->route('dasboard');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
    
        $posts = Post::where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('content', 'like', '%'.$keyword.'%')
                    ->get();
    
        return view('posts.index', compact('posts'));
    }

    public function addComment(Request $request, $postId)
    {
        $validated = $request->validate([
            'content' => 'required',
        ]);

        $comment = new Comment();
        $comment->post_id = $postId;
        $comment->user_id = auth()->id();
        $comment->content = $validated['content'];
        $comment->save();

        return redirect()->back()->with('success', 'Comment added successfully.');
    }
    

}
