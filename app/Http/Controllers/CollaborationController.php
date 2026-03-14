<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collaboration;
use Illuminate\Support\Facades\File;

class CollaborationController extends Controller
{
    // CREATE Collaboration
    public function store(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required',
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

            $collaboration = Collaboration::create([
                'text' => $request->text,
                'image' => $imagePath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
            ]);

            return response()->json([
                'message' => 'Collaboration created successfully',
                'collaborationId' => $collaboration->id,
                'image' => $imagePath
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create collaboration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // READ all collaborations
    public function index()
    {
        try {
            $collaborations = Collaboration::orderBy('id','desc')->get();
            return response()->json($collaborations);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch collaborations'], 500);
        }
    }

    // POST: UPDATE collaboration (changed from PUT to POST)
    public function update(Request $request, $id)
    {
        try {
            $collaboration = Collaboration::findOrFail($id);

            $request->validate([
                'text' => 'required',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            $collaboration->text = $request->text;
            $collaboration->meta_title = $request->meta_title;
            $collaboration->meta_description = $request->meta_description;

            if ($request->hasFile('image')) {
                // Delete old image
                if ($collaboration->image && File::exists(public_path($collaboration->image))) {
                    File::delete(public_path($collaboration->image));
                }
                
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $collaboration->image = '/uploads/'.$imageName;
            }

            $collaboration->save();

            return response()->json([
                'message' => 'Collaboration updated successfully',
                'collaboration' => $collaboration
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update collaboration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE collaboration
    public function destroy($id)
    {
        try {
            $collaboration = Collaboration::findOrFail($id);
            
            // Delete image
            if ($collaboration->image && File::exists(public_path($collaboration->image))) {
                File::delete(public_path($collaboration->image));
            }
            
            $collaboration->delete();

            return response()->json([
                'message' => 'Collaboration deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete collaboration',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}