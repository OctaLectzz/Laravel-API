<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{

    public function like(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $like = $post->likes()->where('user_id', $request->user()->id)->first();
        
        if ($like) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'This Post Already Liked.',
            ], 422);
        }

        try {
            $like = new PostLike;
            $like->user_id = $request->user()->id;
            $like->post_id = $postId;
            $like->save();

            return response()->json([
                'status' => 'Success',
                'message' => 'Post Liked Successfully.',
            ]);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json([
                'status' => 'Failed',
                'message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!'
            ]);
        }
    }

    
    public function unlike(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $like = $post->likes()->where('user_id', $request->user()->id)->first();

        if (!$like) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'This Post Not Liked.',
            ], 422);
        }

        try {
            $like->delete();

            return response()->json([
                'status' => 'Success',
                'message' => 'Post Unliked Successfully.',
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
