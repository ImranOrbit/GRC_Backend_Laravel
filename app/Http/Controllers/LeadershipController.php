<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leadership;
use Illuminate\Support\Facades\Storage;

class LeadershipController extends Controller
{
    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|array',
            'image' => 'required|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        $leader = Leadership::create([
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath ? "/storage/$imagePath" : null,
        ]);

        return response()->json([
            'message' => 'Leader created successfully',
            'id' => $leader->id
        ]);
    }

    // READ all
    public function index()
    {
        $leaders = Leadership::orderBy('id','desc')->get();
        return response()->json($leaders);
    }

    // UPDATE by ID
    public function update(Request $request, $id)
    {
        $leader = Leadership::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|array',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($leader->image) {
                $oldPath = str_replace('/storage/', '', $leader->image);
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $request->file('image')->store('uploads', 'public');
            $leader->image = "/storage/$imagePath";
        }

        $leader->name = $request->name;
        $leader->title = $request->title;
        $leader->description = $request->description;
        $leader->save();

        return response()->json(['message' => 'Leader updated successfully']);
    }

    // DELETE by ID
    public function destroy($id)
    {
        $leader = Leadership::findOrFail($id);

        if ($leader->image) {
            $oldPath = str_replace('/storage/', '', $leader->image);
            Storage::disk('public')->delete($oldPath);
        }

        $leader->delete();

        return response()->json(['message' => 'Leader deleted successfully']);
    }
}