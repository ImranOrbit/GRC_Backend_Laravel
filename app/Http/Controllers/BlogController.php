<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    // GET All blogs
    public function index()
    {
        $blogs = Blog::orderBy('date','desc')->get();
        return response()->json($blogs);
    }

    // GET single blog by slug
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (!$blog) {
            return response()->json(['message'=>'Blog not found'],404);
        }
        return response()->json($blog);
    }

    // POST: Add blog
    public function store(Request $request)
    {
        $request->validate([
            'slug'=>'required|unique:blogs,slug',
            'title'=>'required',
            'excerpt'=>'required',
            'author'=>'required',
            'date'=>'required|date',
            'tag'=>'required',
            'content'=>'required',
            'image'=>'required|image'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
            $imagePath = '/uploads/'.$imageName;
        }

        $blog = Blog::create([
            'slug' => $request->slug,
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'author' => $request->author,
            'date' => $request->date,
            'tag' => $request->tag,
            'content' => $request->content,
            'image' => $imagePath,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description
        ]);

        return response()->json([
            'message'=>'Blog added successfully',
            'blogId'=>$blog->id,
            'image'=>$imagePath
        ]);
    }

    // POST: Edit blog (changed from PUT to POST)
    public function update(Request $request, $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            $request->validate([
                'slug'=>'required|unique:blogs,slug,'.$id,
                'title'=>'required',
                'excerpt'=>'required',
                'author'=>'required',
                'date'=>'required|date',
                'tag'=>'required',
                'content'=>'required',
                'meta_title'=>'nullable|string',
                'meta_description'=>'nullable|string'
            ]);

            $blog->slug = $request->slug;
            $blog->title = $request->title;
            $blog->excerpt = $request->excerpt;
            $blog->author = $request->author;
            $blog->date = $request->date;
            $blog->tag = $request->tag;
            $blog->content = $request->content;
            $blog->meta_title = $request->meta_title;
            $blog->meta_description = $request->meta_description;

            // Handle image upload
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
                'message'=>'Blog updated successfully',
                'blog' => $blog
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE blog
    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            
            // Delete image
            if ($blog->image && File::exists(public_path($blog->image))) {
                File::delete(public_path($blog->image));
            }
            
            $blog->delete();

            return response()->json(['message'=>'Blog deleted successfully']);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}