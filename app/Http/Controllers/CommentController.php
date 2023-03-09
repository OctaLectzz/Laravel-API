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
    public function index()
    {
        $comments = Comment::paginate(10)->latest()->get();

        return response()->json(['data' => $comments]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // $postId)
    {
        $validatedData = $request->validate([
            'content' => 'required'
        ],
        [
            'content.required' => 'Comment wajib di isi'
        ]);
        $validatedData['user_id']  = auth()->id();
        $validatedData['post_id']  = 1; // $postId;


        try {
            $comment = Comment::create($validatedData);
         
            return response()->json([
                'status' => 'Success',
                'message' => 'Comment Created Successfully!',
                'data' => $comment
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
    public function show(Comment $comment)
    {
        return response()->json(['data' => $comment]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Check User
        $this->middleware('check.comment.ownership')->only('update');


        $request->validate([
            'content' => 'required'
        ],
        [
            'content.required' => 'Comment wajib di isi'
        ]);

        
        try {
            $comment = Comment::findOrFail($id);

            $data = $request->all();
            $data['user_id'] = auth()->id();
            $comment->update($data);
         
            return response()->json([
                'status' => 'Success',
                'message' => 'Comment Updated Successfully!',
                'data' => $comment
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
    public function destroy(Comment $comment)
    {
        // Check User
        $this->middleware('check.comment.ownership')->only('delete');


        try {
            $comment->delete();
    
            return response()->json([
                'status' => 'Success',
                'message' => 'Comment Deleted Successfully!'
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
