<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReviewTwo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ReviewTwoController extends Controller
{
    // POST: Submit reviewtwo
    public function submitReviewTwo(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'review_text' => 'required',
                'image' => 'nullable|image|max:2048',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $imagePath = '/uploads/'.$imageName;
            }

            $review = ReviewTwo::create([
                'name' => $request->name,
                'review_text' => $request->review_text,
                'image_url' => $imagePath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
            ]);

            return response()->json([
                'message' => 'Review (two) submitted successfully',
                'reviewId' => $review->id
            ]);
            
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
            $reviews = ReviewTwo::orderBy('created_at','DESC')->get();
            return response()->json($reviews);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch reviews'], 500);
        }
    }

    // POST: Update review (changed from PUT to POST)
    public function update(Request $request, $id)
    {
        try {
            $review = ReviewTwo::findOrFail($id);

            $request->validate([
                'name' => 'required',
                'review_text' => 'required',
                'image' => 'nullable|image|max:2048',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($review->image_url) {
                    $oldPath = public_path($review->image_url);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $review->image_url = '/uploads/'.$imageName;
            }

            $review->name = $request->name;
            $review->review_text = $request->review_text;
            $review->meta_title = $request->meta_title;
            $review->meta_description = $request->meta_description;
            $review->save();

            return response()->json([
                'message' => 'Review updated successfully',
                'review' => $review
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE: Delete review
    public function destroy($id)
    {
        try {
            $review = ReviewTwo::findOrFail($id);

            if ($review->image_url) {
                $oldPath = public_path($review->image_url);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $review->delete();

            return response()->json([
                'message' => 'Review deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}