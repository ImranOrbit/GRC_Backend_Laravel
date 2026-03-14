<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisaSuccess;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class VisaSuccessController extends Controller
{
    // GET ALL VISA SUCCESS
    public function index()
    {
        try {
            $data = VisaSuccess::orderBy('created_at', 'DESC')->get();
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Failed to fetch visa success: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch data'], 500);
        }
    }

    // ADD VISA SUCCESS
    public function store(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required',
                'image' => 'required|image|max:2048',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $imagePath = '/uploads/' . $imageName;
            }

            $entry = VisaSuccess::create([
                'text' => $request->text,
                'image' => $imagePath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
            ]);

            Log::info('Visa success entry created:', ['id' => $entry->id]);

            return response()->json([
                'message' => 'Visa success entry added',
                'id' => $entry->id,
                'data' => $entry
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to create visa success: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST: UPDATE VISA SUCCESS (changed from PUT to POST)
    public function update(Request $request, $id)
    {
        try {
            $entry = VisaSuccess::findOrFail($id);

            $request->validate([
                'text' => 'required',
                'image' => 'nullable|image|max:2048',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($entry->image) {
                    $oldPath = public_path($entry->image);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $entry->image = '/uploads/' . $imageName;
            }

            $entry->text = $request->text;
            $entry->meta_title = $request->meta_title;
            $entry->meta_description = $request->meta_description;
            $entry->save();

            Log::info('Visa success entry updated:', ['id' => $id]);

            return response()->json([
                'message' => 'Entry updated successfully',
                'data' => $entry
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to update visa success: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE VISA SUCCESS
    public function destroy($id)
    {
        try {
            $entry = VisaSuccess::findOrFail($id);

            if ($entry->image) {
                $oldPath = public_path($entry->image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $entry->delete();

            Log::info('Visa success entry deleted:', ['id' => $id]);

            return response()->json([
                'message' => 'Entry deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to delete visa success: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}