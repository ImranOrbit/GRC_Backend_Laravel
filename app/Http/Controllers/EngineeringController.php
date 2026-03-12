<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EngineeringBlog;
use Illuminate\Support\Facades\Storage;

class EngineeringController extends Controller
{
    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'content' => 'required|string',
            'image' => 'required|image|max:2048', // optional size limit
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        $blog = EngineeringBlog::create([
            'text' => $request->text,
            'content' => $request->content,
            'image' => $imagePath ? "/storage/$imagePath" : null,
        ]);

        return response()->json([
            'message' => 'Engineering blog inserted successfully',
            'blogId' => $blog->id,
            'image' => $blog->image
        ]);
    }

    // GET all blogs
    public function index()
    {
        $blogs = EngineeringBlog::orderBy('id','desc')->get();
        return response()->json($blogs);
    }

    // UPDATE by ID
    public function update(Request $request, $id)
    {
        $blog = EngineeringBlog::findOrFail($id);

        $request->validate([
            'text' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($blog->image) {
                $oldPath = str_replace('/storage/', '', $blog->image);
                Storage::disk('public')->delete($oldPath);
            }

            $imagePath = $request->file('image')->store('uploads', 'public');
            $blog->image = "/storage/$imagePath";
        }

        $blog->text = $request->text;
        $blog->content = $request->content;
        $blog->save();

        return response()->json(['message' => 'Blog updated successfully']);
    }

    // DELETE by ID
    public function destroy($id)
    {
        $blog = EngineeringBlog::findOrFail($id);

        if ($blog->image) {
            $oldPath = str_replace('/storage/', '', $blog->image);
            Storage::disk('public')->delete($oldPath);
        }

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully']);
    }
}