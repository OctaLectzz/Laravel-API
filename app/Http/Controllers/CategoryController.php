<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return CategoryResource::collection($categories);
    }

    public function getpost()
    {
        $categoryPost = Category::latest()->get();

        return CategoryResource::collection($categoryPost);
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
            $category = Category::create($validatedData);

            return response()->json([
                'status' => 'Success',
                'message' => 'Category Created Successfully!',
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
        $category = Category::findOrFail($id);

        return response()->json([
            'data' => new CategoryResource($category)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
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
            $category->update($validatedData);

            return response()->json([
                'status' => 'Success',
                'message' => 'Category Updated Successfully!',
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
    public function destroy(Category $category)
    {
        try {
            $category->delete();
    
            return response()->json([
                'status' => 'Success',
                'message' => 'Category Deleted Successfully!'
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
