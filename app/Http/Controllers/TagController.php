<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::paginate(10);

        return TagResource::collection($tags);
    }

    public function getpost()
    {
        $tagPost = Tag::latest()->get();

        return TagResource::collection($tagPost);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate Request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'max:255'
        ],
        [
            'name.required' => 'Nama wajib di isi',
            'name.string' => 'Nama harus bernilai string',
            'name.max:255' => 'Nama Maximal 255',
            'description' => 'Deskripsi Maximal 255'
        ]);
        $validatedData['created_by'] = Auth::user()->name;


        try {
            $tag = Tag::create($validatedData);

            return response()->json([
                'status' => 'Success',
                'message' => 'Tag Created Successfully!',
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
        $tag = Tag::findOrFail($id);

        return response()->json([
            'data' => new TagResource($tag)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        // Validate Request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'max:255'
        ],
        [
            'name.required' => 'Nama wajib di isi',
            'name.string' => 'Nama harus bernilai string',
            'name.max:255' => 'Nama Maximal 255',
            'description' => 'Deskripsi Maximal 255'
        ]);
        $validatedData['created_by'] = Auth::user()->name;


        try {
            $tag->update($validatedData);

            return response()->json([
                'status' => 'Success',
                'message' => 'Tag Updated Successfully!',
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
    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();
    
            return response()->json([
                'status' => 'Success',
                'message' => 'Tag Deleted Successfully!'
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
