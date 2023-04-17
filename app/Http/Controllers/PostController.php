<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Post $post)
    {
        $tag = $request->query('tag');

        $posts = $tag ? $post->whereHas('tag', function ($query) use ($tag) {
            $query->where('name', $tag);
        })->paginate(20) : $post->paginate(12);

        return PostResource::collection($posts);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'body' => 'required',
            'tag' => 'integer'
        ],
        [
            'title.required' => 'Judul wajib di isi',
            'title.string' => 'Judul harus bernilai string',
            'body.required' => 'Post wajib memiliki konten',
            'tag.integer' => 'pastikan anda memasukan id Tag yang benar'
        ]);
        $validatedData['created_by'] = Auth::user()->name;
        if ($request->hasFile('postImages')) {
            $newPostImages = $request->postImages->getClientOriginalName();
            $request->postImages->storeAs('public/postImages', $newPostImages);
            $validatedData['postImages'] = $newPostImages;
        }

        
        try {
            $post = Post::create($validatedData);
            $post->tag()->attach($request->tags);
            $post->category()->attach($request->categories);

            return response()->json([
                'status' => 'Success',
                'message' => 'Post Created Successfully!',
            ]);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json([
                'status' => 'Failed',
                'message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!'
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        $post->views++;
        $post->save();
        
        return response()->json([
            'data' => new PostResource($post)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate(
            [
                'title' => 'required|string',
                'body' => 'required',
            ],
            [
                'title.required' => 'Judul wajib di isi',
                'title.string' => 'Judul harus bernilai string',
                'body.required' => 'Post wajib memiliki konten'
            ]);
            $validatedData['created_by'] = auth()->user()->name;
            if ($request->hasFile('postImages')) {
                $newPostImages = $request->postImages->getClientOriginalName();
                $request->postImages->storeAs('public/postImages', $newPostImages);
                $validatedData['postImages'] = $newPostImages;
            }

            
        try {
            $post->tag()->sync($request->tags);
            $post->category()->sync($request->categories);
            $post->update($validatedData);

            return response()->json([
                'status' => 'Success',
                'message' => 'Post Updated Successfully!',
            ]);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json([
                'status' => 'Failed',
                'message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!'
            ]);
        }
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            $post->tag()->detach();
            $post->category()->detach();
            $post->delete();
    
            return response()->json([
                'status' => 'Success',
                'message' => 'Post Deleted Successfully!'
            ]);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json([
                'status' => 'Failed',
                'message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!'
            ]);
        }
    }
    
}
