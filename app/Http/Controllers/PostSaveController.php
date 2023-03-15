<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostSave;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class PostSaveController extends Controller
{

    public function index()
    {
        $savedPosts = PostSave::where('user_id', auth()->id())->with('post')->get();

        return response()->json([
            'data' => $savedPosts
        ]);
    }

    
    public function save(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $save = $post->saves()->where('user_id', $request->user()->id)->first();
        
        if ($save) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'This Post Already Saved.',
            ], 422);
        }

        try {
            $save = new PostSave;
            $save->user_id = $request->user()->id;
            $save->post_id = $postId;
            $save->save();

            return response()->json([
                'status' => 'Success',
                'message' => 'Post Saved Successfully.',
            ]);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json([
                'status' => 'Failed',
                'message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!'
            ]);
        }
    }

    
    public function unsave(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $save = $post->saves()->where('user_id', $request->user()->id)->first();

        if (!$save) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'This Post Not Saved.',
            ], 422);
        }

        try {
            $save->delete();

            return response()->json([
                'status' => 'Success',
                'message' => 'Post Unsaved Successfully.',
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
