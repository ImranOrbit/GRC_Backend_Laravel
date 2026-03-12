<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collaboration;

class CollaborationController extends Controller
{
    // CREATE Collaboration
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'image' => 'required|image'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
            $imagePath = '/uploads/'.$imageName;
        }

        $collaboration = Collaboration::create([
            'text' => $request->text,
            'image' => $imagePath
        ]);

        return response()->json([
            'message' => 'Collaboration created successfully',
            'collaborationId' => $collaboration->id,
            'image' => $imagePath
        ]);
    }

    // READ all collaborations
    public function index()
    {
        $collaborations = Collaboration::orderBy('id','desc')->get();
        return response()->json($collaborations);
    }

    // UPDATE collaboration
    public function update(Request $request, $id)
    {
        $collaboration = Collaboration::findOrFail($id);

        $request->validate([
            'text' => 'required'
        ]);

        $collaboration->text = $request->text;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
            $collaboration->image = '/uploads/'.$imageName;
        }

        $collaboration->save();

        return response()->json([
            'message' => 'Collaboration updated successfully'
        ]);
    }

    // DELETE collaboration
    public function destroy($id)
    {
        $collaboration = Collaboration::findOrFail($id);
        $collaboration->delete();

        return response()->json([
            'message' => 'Collaboration deleted successfully'
        ]);
    }
}