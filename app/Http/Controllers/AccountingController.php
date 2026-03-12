<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{

    // POST: Insert accounting blog
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'content' => 'required',
            'image' => 'required|image'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
            $imagePath = '/uploads/'.$imageName;
        }

        $id = DB::table('accounting_blog')->insertGetId([
            'text' => $request->text,
            'image' => $imagePath,
            'content' => $request->content
        ]);

        return response()->json([
            'message' => 'Course blog inserted successfully',
            'blogId' => $id,
            'image' => $imagePath
        ]);
    }


    // GET: Fetch all accounting blog
    public function index()
    {
        $blogs = DB::table('accounting_blog')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($blogs);
    }


    // PUT: Update blog
    public function update(Request $request, $id)
    {
        $data = [
            'text' => $request->text,
            'content' => $request->content
        ];

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
            $data['image'] = '/uploads/'.$imageName;
        }

        DB::table('accounting_blog')
            ->where('id', $id)
            ->update($data);

        return response()->json([
            'message' => 'Blog updated successfully'
        ]);
    }


    // DELETE blog
    public function destroy($id)
    {
        DB::table('accounting_blog')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'Blog deleted successfully'
        ]);
    }

}
