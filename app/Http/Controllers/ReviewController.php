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
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads','public');
        }

        Review::create([
            'name' => $request->name,
            'review_text' => $request->review_text,
            'rating' => $request->rating,
            'image_url' => $imagePath ? "/storage/".$imagePath : null
        ]);

        return response()->json([
            'message' => 'Review submitted successfully'
        ]);
    }


    // GET: All reviews
    public function index()
    {
        $reviews = Review::orderBy('created_at','DESC')->get();

        return response()->json($reviews);
    }


    // PUT: Update review
    public function update(Request $request, $id)
    {

        $review = Review::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'review_text' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {

            if ($review->image_url) {
                $oldPath = str_replace('/storage/','',$review->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $imagePath = $request->file('image')->store('uploads','public');
            $review->image_url = "/storage/".$imagePath;
        }

        $review->name = $request->name;
        $review->review_text = $request->review_text;
        $review->rating = $request->rating;

        $review->save();

        return response()->json([
            'message' => 'Review updated successfully'
        ]);
    }


    // DELETE: Remove review
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if ($review->image_url) {
            $oldPath = str_replace('/storage/','',$review->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully'
        ]);
    }

}