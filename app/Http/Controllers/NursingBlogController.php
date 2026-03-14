<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NursingBlog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class NursingBlogController extends Controller
{
    // POST: Insert nursing blog
    public function store(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required',
                'content' => 'required',
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

            $blog = NursingBlog::create([
                'text' => $request->text,
                'content' => $request->content,
                'image' => $imagePath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
            ]);

            return response()->json([
                'message' => 'Nurse Create successfully',
                'blogId' => $blog->id,
                'image' => $blog->image
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create nurse blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET: Fetch all nursing blog entries
    public function index()
    {
        try {
            $blogs = NursingBlog::orderBy('id','DESC')->get();
            return response()->json($blogs);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch blogs'], 500);
        }
    }

    // POST: Update nursing blog by ID (changed from PUT to POST)
    public function update(Request $request, $id)
    {
        try {
            $blog = NursingBlog::findOrFail($id);

            $request->validate([
                'text' => 'required',
                'content' => 'required',
                'image' => 'nullable|image|max:2048',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            if ($request->hasFile('image')) {
                // Delete old image
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
                'message' => 'Nurse updated successfully',
                'blog' => $blog
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update nurse blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE: Delete nursing blog by ID
    public function destroy($id)
    {
        try {
            $blog = NursingBlog::findOrFail($id);

            // Delete image
            if ($blog->image) {
                $oldPath = public_path($blog->image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $blog->delete();

            return response()->json([
                'message' => 'Nurse deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete nurse blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}