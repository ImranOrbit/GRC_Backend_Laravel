<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

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
            'image' => $imagePath
        ]);

        return response()->json([
            'message'=>'Blog added successfully',
            'blogId'=>$blog->id,
            'image'=>$imagePath
        ]);
    }

    // PUT: Edit blog
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'slug'=>'required|unique:blogs,slug,'.$id,
            'title'=>'required',
            'excerpt'=>'required',
            'author'=>'required',
            'date'=>'required|date',
            'tag'=>'required',
            'content'=>'required',
        ]);

        $blog->slug = $request->slug;
        $blog->title = $request->title;
        $blog->excerpt = $request->excerpt;
        $blog->author = $request->author;
        $blog->date = $request->date;
        $blog->tag = $request->tag;
        $blog->content = $request->content;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
            $blog->image = '/uploads/'.$imageName;
        } elseif ($request->existingImage) {
            $blog->image = $request->existingImage;
        }

        $blog->save();

        return response()->json(['message'=>'Blog updated successfully']);
    }

    // DELETE blog
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return response()->json(['message'=>'Blog deleted successfully']);
    }
}