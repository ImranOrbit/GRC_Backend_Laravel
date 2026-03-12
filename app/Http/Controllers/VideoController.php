<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{

    // INSERT or UPDATE VIDEO URL
    public function updateVideoUrl(Request $request)
    {

        $request->validate([
            'url' => 'required'
        ]);

        $id = $request->id;

        if ($id) {

            $video = Video::find($id);

            if (!$video) {
                return response()->json([
                    'message' => 'Video ID not found'
                ],404);
            }

            $video->url = $request->url;
            $video->save();

            return response()->json([
                'message' => 'Video URL updated successfully'
            ]);

        } else {

            $video = Video::create([
                'url' => $request->url
            ]);

            return response()->json([
                'message' => 'Video URL added successfully',
                'id' => $video->id
            ]);

        }

    }


    // GET ALL VIDEOS
    public function getVideoUrls()
    {
        $videos = Video::orderBy('id','ASC')->get();

        return response()->json($videos);
    }

}