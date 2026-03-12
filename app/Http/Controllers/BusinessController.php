<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessBlog;

class BusinessController extends Controller
{
    // POST: Add business blog
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'content' => 'required',
            'image' => 'required|image'
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
            'image' => $imagePath
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

    // PUT: Update business blog
    public function update(Request $request, $id)
    {
        $blog = BusinessBlog::findOrFail($id);

        $request->validate([
            'text' => 'required',
            'content' => 'required'
        ]);

        $blog->text = $request->text;
        $blog->content = $request->content;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
            $blog->image = '/uploads/'.$imageName;
        }

        $blog->save();

        return response()->json([
            'message' => 'Business blog updated successfully'
        ]);
    }

    // DELETE: Remove business blog
    public function destroy($id)
    {
        $blog = BusinessBlog::findOrFail($id);
        $blog->delete();

        return response()->json([
            'message' => 'Business blog deleted successfully'
        ]);
    }
}