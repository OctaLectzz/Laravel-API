<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate(10);

        return response()->json(['data' => $posts]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'body' => 'required',
        ],
        [
            'title.required' => 'Judul wajib di isi',
            'title.string' => 'Judul harus bernilai string',
            'body.required' => 'Post wajib memiliki konten'
        ]);
        $validatedData['created_by'] = Auth::user()->name;

        
        try {
            $post = Post::create($validatedData);

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
        
        return response()->json(['data' => $post]);
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

            
        try {
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
