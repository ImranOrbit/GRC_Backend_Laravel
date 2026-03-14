<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EngineeringBlog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class EngineeringController extends Controller
{
    // CREATE
    public function store(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string',
                'content' => 'required|string',
                'image' => 'required|image|max:2048',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $imagePath = '/uploads/'.$imageName;
            }

            $blog = EngineeringBlog::create([
                'text' => $request->text,
                'content' => $request->content,
                'image' => $imagePath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
            ]);

            return response()->json([
                'message' => 'Engineering blog inserted successfully',
                'blogId' => $blog->id,
                'image' => $blog->image
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create engineering blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET all blogs
    public function index()
    {
        try {
            $blogs = EngineeringBlog::orderBy('id','desc')->get();
            return response()->json($blogs);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch blogs'], 500);
        }
    }

    // POST: UPDATE by ID (changed from PUT to POST)
    public function update(Request $request, $id)
    {
        try {
            $blog = EngineeringBlog::findOrFail($id);

            $request->validate([
                'text' => 'required|string',
                'content' => 'required|string',
                'image' => 'nullable|image|max:2048',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($blog->image) {
                    $oldPath = public_path($blog->image);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $blog->image = '/uploads/'.$imageName;
            }

            $blog->text = $request->text;
            $blog->content = $request->content;
            $blog->meta_title = $request->meta_title;
            $blog->meta_description = $request->meta_description;
            $blog->save();

            return response()->json([
                'message' => 'Blog updated successfully',
                'blog' => $blog
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE by ID
    public function destroy($id)
    {
        try {
            $blog = EngineeringBlog::findOrFail($id);

            if ($blog->image) {
                $oldPath = public_path($blog->image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $blog->delete();

            return response()->json(['message' => 'Blog deleted successfully']);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}