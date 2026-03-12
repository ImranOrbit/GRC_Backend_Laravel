<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisaSuccess;
use Illuminate\Support\Facades\Storage;

class VisaSuccessController extends Controller
{

    // GET ALL VISA SUCCESS
    public function index()
    {
        $data = VisaSuccess::orderBy('created_at','DESC')->get();

        return response()->json($data);
    }


    // ADD VISA SUCCESS
    public function store(Request $request)
    {

        $request->validate([
            'text' => 'required',
            'image' => 'required|image|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads','public');
        }

        $entry = VisaSuccess::create([
            'text' => $request->text,
            'image' => "/storage/".$imagePath
        ]);

        return response()->json([
            'message' => 'Visa success entry added',
            'id' => $entry->id
        ]);
    }


    // UPDATE VISA SUCCESS
    public function update(Request $request, $id)
    {

        $entry = VisaSuccess::findOrFail($id);

        $request->validate([
            'text' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {

            if ($entry->image) {
                $oldPath = str_replace('/storage/','',$entry->image);
                Storage::disk('public')->delete($oldPath);
            }

            $imagePath = $request->file('image')->store('uploads','public');
            $entry->image = "/storage/".$imagePath;
        }

        $entry->text = $request->text;
        $entry->save();

        return response()->json([
            'message' => 'Entry updated successfully'
        ]);
    }


    // DELETE VISA SUCCESS
    public function destroy($id)
    {

        $entry = VisaSuccess::findOrFail($id);

        if ($entry->image) {
            $oldPath = str_replace('/storage/','',$entry->image);
            Storage::disk('public')->delete($oldPath);
        }

        $entry->delete();

        return response()->json([
            'message' => 'Entry deleted successfully'
        ]);
    }

}