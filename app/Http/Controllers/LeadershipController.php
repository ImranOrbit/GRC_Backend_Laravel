<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leadership;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LeadershipController extends Controller
{
    // CREATE
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'title' => 'required|string',
                'description' => 'required|string',
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

            $leader = Leadership::create([
                'name' => $request->name,
                'title' => $request->title,
                'description' => $request->description,
                'image' => $imagePath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
            ]);

            return response()->json([
                'message' => 'Leader created successfully',
                'id' => $leader->id
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create leader',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // READ all
    public function index()
    {
        try {
            $leaders = Leadership::orderBy('id','desc')->get();
            return response()->json($leaders);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch leaders'], 500);
        }
    }

    // POST: UPDATE by ID (changed from PUT to POST)
    public function update(Request $request, $id)
    {
        try {
            $leader = Leadership::findOrFail($id);

            $request->validate([
                'name' => 'required|string',
                'title' => 'required|string',
                'description' => 'required|string',
                'image' => 'nullable|image|max:2048',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($leader->image) {
                    $oldPath = public_path($leader->image);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $leader->image = '/uploads/'.$imageName;
            }

            $leader->name = $request->name;
            $leader->title = $request->title;
            $leader->description = $request->description;
            $leader->meta_title = $request->meta_title;
            $leader->meta_description = $request->meta_description;
            $leader->save();

            return response()->json([
                'message' => 'Leader updated successfully',
                'leader' => $leader
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update leader',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE by ID
    public function destroy($id)
    {
        try {
            $leader = Leadership::findOrFail($id);

            if ($leader->image) {
                $oldPath = public_path($leader->image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $leader->delete();

            return response()->json(['message' => 'Leader deleted successfully']);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete leader',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}