<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AccountingController extends Controller
{
    // POST: Insert accounting blog
    public function store(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required',
                'content' => 'required',
                'image' => 'required|image',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $imagePath = '/uploads/' . $imageName;
            }

            $id = DB::table('accounting_blog')->insertGetId([
                'text' => $request->text,
                'image' => $imagePath,
                'content' => $request->content,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info('Accounting blog created:', ['id' => $id]);

            return response()->json([
                'message' => 'Course blog inserted successfully',
                'blogId' => $id,
                'image' => $imagePath
            ]);
            
        } catch (\Exception $e) {
            Log::error('Store failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to insert blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET: Fetch all accounting blog
    public function index()
    {
        try {
            $blogs = DB::table('accounting_blog')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($blogs);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch blogs'], 500);
        }
    }

    // POST: Update blog (handles both POST and PUT)
    public function update(Request $request, $id)
    {
        try {
            Log::info('Update request received for ID: ' . $id);
            Log::info('Request method: ' . $request->method());
            Log::info('Request data:', $request->all());

            $request->validate([
                'text' => 'required',
                'content' => 'required',
                'image' => 'nullable|image',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            // Get the existing record
            $existingBlog = DB::table('accounting_blog')->where('id', $id)->first();
            
            if (!$existingBlog) {
                return response()->json(['message' => 'Blog not found'], 404);
            }

            $data = [
                'text' => $request->text,
                'content' => $request->content,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'updated_at' => now()
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($existingBlog->image) {
                    $oldImagePath = public_path($existingBlog->image);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                        Log::info('Old image deleted: ' . $existingBlog->image);
                    }
                }
                
                // Upload new image
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $data['image'] = '/uploads/' . $imageName;
                Log::info('New image uploaded: ' . $data['image']);
            }

            DB::table('accounting_blog')
                ->where('id', $id)
                ->update($data);

            Log::info('Blog updated successfully for ID: ' . $id);

            return response()->json([
                'message' => 'Blog updated successfully',
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE blog
    public function destroy($id)
    {
        try {
            // Get the blog to delete its image
            $blog = DB::table('accounting_blog')->where('id', $id)->first();
            
            if (!$blog) {
                return response()->json(['message' => 'Blog not found'], 404);
            }

            if ($blog && $blog->image) {
                $imagePath = public_path($blog->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                    Log::info('Image deleted: ' . $blog->image);
                }
            }

            DB::table('accounting_blog')
                ->where('id', $id)
                ->delete();

            Log::info('Blog deleted successfully for ID: ' . $id);

            return response()->json([
                'message' => 'Blog deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Delete failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}