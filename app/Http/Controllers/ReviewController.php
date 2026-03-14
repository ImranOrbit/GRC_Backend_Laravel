<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{

    // POST: Submit review
    public function submitReview(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'review_text' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        Review::create([
            'name' => $request->name,
            'review_text' => $request->review_text,
            'rating' => $request->rating,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'image_url' => $imagePath ? "/storage/" . $imagePath : null
        ]);

        return response()->json([
            'message' => 'Review submitted successfully'
        ]);
    }

    // GET: All reviews
    public function index()
    {
        $reviews = Review::orderBy('created_at', 'DESC')->get();
        return response()->json($reviews);
    }

    // POST: Update review (changed from PUT to POST)
    public function update(Request $request, $id)
    {
        try {
            $review = Review::findOrFail($id);

            $request->validate([
                'name' => 'required',
                'review_text' => 'required',
                'rating' => 'required|integer|min:1|max:5',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string',
                'image' => 'nullable|image|max:2048'
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($review->image_url) {
                    $oldPath = str_replace('/storage/', '', $review->image_url);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                // Upload new image
                $imagePath = $request->file('image')->store('reviews', 'public');
                $review->image_url = "/storage/" . $imagePath;
            }

            // Update other fields
            $review->name = $request->name;
            $review->review_text = $request->review_text;
            $review->rating = $request->rating;
            $review->meta_title = $request->meta_title;
            $review->meta_description = $request->meta_description;

            $review->save();

            return response()->json([
                'message' => 'Review updated successfully',
                'review' => $review
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE review
    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);

            // Delete image if exists
            if ($review->image_url) {
                $oldPath = str_replace('/storage/', '', $review->image_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $review->delete();

            return response()->json([
                'message' => 'Review deleted successfully'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}