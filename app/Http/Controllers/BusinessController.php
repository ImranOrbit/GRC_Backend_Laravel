<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessBlog;
use Illuminate\Support\Facades\File;

class BusinessController extends Controller
{
    // POST: Add business blog
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'content' => 'required',
            'image' => 'required|image',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
            $imagePath = '/uploads/'.$imageName;
        }

        $blog = BusinessBlog::create([
            'text' => $request->text,
            'content' => $request->content,
            'image' => $imagePath,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description
        ]);

        return response()->json([
            'message' => 'Business blog inserted successfully',
            'blogId' => $blog->id,
            'image' => $imagePath
        ]);
    }

    // GET: All business blogs
    public function index()
    {
        $blogs = BusinessBlog::orderBy('id','desc')->get();
        return response()->json($blogs);
    }

    // POST: Update business blog (changed from PUT to POST)
    public function update(Request $request, $id)
    {
        try {
            $blog = BusinessBlog::findOrFail($id);

            $request->validate([
                'text' => 'required',
                'content' => 'required',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            $blog->text = $request->text;
            $blog->content = $request->content;
            $blog->meta_title = $request->meta_title;
            $blog->meta_description = $request->meta_description;

            if ($request->hasFile('image')) {
                // Delete old image
                if ($blog->image && File::exists(public_path($blog->image))) {
                    File::delete(public_path($blog->image));
                }
                
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $blog->image = '/uploads/'.$imageName;
            }

            $blog->save();

            return response()->json([
                'message' => 'Business blog updated successfully',
                'blog' => $blog
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE: Remove business blog
    public function destroy($id)
    {
        try {
            $blog = BusinessBlog::findOrFail($id);
            
            // Delete image
            if ($blog->image && File::exists(public_path($blog->image))) {
                File::delete(public_path($blog->image));
            }
            
            $blog->delete();

            return response()->json([
                'message' => 'Business blog deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}