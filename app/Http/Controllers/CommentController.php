<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        return $post->comments()->latest()->get();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $postId)
    {
        $validatedData = $request->validate([
            'content' => 'required'
        ]);
        $validatedData['user_id']  = auth()->id();
        $validatedData['post_id']  = $postId;

        try {
            $comment = Comment::create($validatedData);
         
            return response()->json([
                'message' => 'Comment Created Successfully',
                'data' => $comment
            ], 201);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json(['message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!']);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return response()->json(['data' => $comment]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment, $postId)
    {
        $validatedData = $request->validate([
            'content' => 'required'
        ]);
        $validatedData['user_id']  = 1; // auth()->id();
        $validatedData['post_id']  = $postId;

        try {
            $comment->update($validatedData);
         
            return response()->json(['message' => 'Comment Updated Successfully', 'data' => $comment]);
        } catch (\Throwable $th) {
            info($th);
            return response()->json(['message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
    
            return response()->json(['message' => 'Comment Deleted Successfully!']);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json(['message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!']);
        }
    }
}
