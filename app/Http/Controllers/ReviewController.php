<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\File;

class ReviewController extends Controller
{
    // POST: Submit review
    public function submitReview(Request $request)
    {
        try {
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
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $imagePath = '/uploads/' . $imageName;
                
                // Debug: Log the image path
                \Log::info('Image uploaded: ' . $imagePath);
            }

            $review = Review::create([
                'name' => $request->name,
                'review_text' => $request->review_text,
                'rating' => $request->rating,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'image_url' => $imagePath
            ]);

            return response()->json([
                'message' => 'Review submitted successfully',
                'reviewId' => $review->id,
                'image_url' => $imagePath
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to submit review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET: All reviews
    public function index()
    {
        try {
            $reviews = Review::orderBy('created_at', 'DESC')->get();
            return response()->json($reviews, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST: Update review
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
                    $oldPath = public_path($review->image_url);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                        \Log::info('Old image deleted: ' . $oldPath);
                    }
                }

                // Upload new image
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $review->image_url = '/uploads/' . $imageName;
                
                \Log::info('New image uploaded: ' . $review->image_url);
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
                $oldPath = public_path($review->image_url);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                    \Log::info('Image deleted: ' . $oldPath);
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