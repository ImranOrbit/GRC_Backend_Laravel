<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NursingBlog;
use Illuminate\Support\Facades\Storage;

class NursingBlogController extends Controller
{

    // POST: Insert nursing blog
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'content' => 'required',
            'image' => 'required|image|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads','public');
        }

        $blog = NursingBlog::create([
            'text' => $request->text,
            'content' => $request->content,
            'image' => $imagePath ? "/storage/".$imagePath : null
        ]);

        return response()->json([
            'message' => 'Nurse Create successfully',
            'blogId' => $blog->id,
            'image' => $blog->image
        ]);
    }


    // GET: Fetch all nursing blog entries
    public function index()
    {
        $blogs = NursingBlog::orderBy('id','DESC')->get();

        return response()->json($blogs);
    }


    // PUT: Update nursing blog by ID
    public function update(Request $request, $id)
    {
        $blog = NursingBlog::findOrFail($id);

        $request->validate([
            'text' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {

            if ($blog->image) {
                $oldPath = str_replace('/storage/','',$blog->image);
                Storage::disk('public')->delete($oldPath);
            }

            $imagePath = $request->file('image')->store('uploads','public');
            $blog->image = "/storage/".$imagePath;
        }

        $blog->text = $request->text;
        $blog->content = $request->content;

        $blog->save();

        return response()->json([
            'message' => 'Nurse updated successfully'
        ]);
    }


    // DELETE: Delete nursing blog by ID
    public function destroy($id)
    {
        $blog = NursingBlog::findOrFail($id);

        if ($blog->image) {
            $oldPath = str_replace('/storage/','',$blog->image);
            Storage::disk('public')->delete($oldPath);
        }

        $blog->delete();

        return response()->json([
            'message' => 'Nurse deleted successfully'
        ]);
    }

}