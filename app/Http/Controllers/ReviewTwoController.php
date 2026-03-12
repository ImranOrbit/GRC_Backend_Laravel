<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReviewTwo;
use Illuminate\Support\Facades\Storage;

class ReviewTwoController extends Controller
{

    // POST: Submit reviewtwo
    public function submitReviewTwo(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'review_text' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads','public');
        }

        ReviewTwo::create([
            'name' => $request->name,
            'review_text' => $request->review_text,
            'image_url' => $imagePath ? "/storage/".$imagePath : null
        ]);

        return response()->json([
            'message' => 'Review (two) submitted successfully'
        ]);
    }


    // GET: All reviews
    public function index()
    {
        $reviews = ReviewTwo::orderBy('created_at','DESC')->get();

        return response()->json($reviews);
    }


    // PUT: Update review
    public function update(Request $request, $id)
    {
        $review = ReviewTwo::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'review_text' => 'required',
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

        $review->save();

        return response()->json([
            'message' => 'Review updated successfully'
        ]);
    }


    // DELETE: Delete review
    public function destroy($id)
    {
        $review = ReviewTwo::findOrFail($id);

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