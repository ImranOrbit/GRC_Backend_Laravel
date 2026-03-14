<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    // INSERT or UPDATE SINGLE VIDEO URL
    public function updateVideoUrl(Request $request)
    {
        try {
            $request->validate([
                'url' => 'required',
                'title' => 'nullable|string',
                'thumbnail' => 'nullable|string',
                'tags' => 'nullable|array',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string'
            ]);

            $id = $request->id;

            if ($id) {
                $video = Video::find($id);
                if (!$video) {
                    return response()->json([
                        'message' => 'Video ID not found'
                    ], 404);
                }

                $video->url = $request->url;
                if ($request->has('title')) {
                    $video->title = $request->title;
                }
                if ($request->has('thumbnail')) {
                    $video->thumbnail = $request->thumbnail;
                }
                if ($request->has('tags')) {
                    $video->tags = $request->tags;
                }
                if ($request->has('meta_title')) {
                    $video->meta_title = $request->meta_title;
                }
                if ($request->has('meta_description')) {
                    $video->meta_description = $request->meta_description;
                }
                $video->save();

                Log::info('Video updated:', ['id' => $id, 'data' => $video->toArray()]);

                return response()->json([
                    'message' => 'Video URL updated successfully',
                    'data' => $video
                ]);
            } else {
                $video = Video::create([
                    'url' => $request->url,
                    'title' => $request->title,
                    'thumbnail' => $request->thumbnail,
                    'tags' => $request->tags,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description
                ]);

                Log::info('Video created:', ['id' => $video->id]);

                return response()->json([
                    'message' => 'Video URL added successfully',
                    'id' => $video->id,
                    'data' => $video
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Video update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update video',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // UPDATE MULTIPLE VIDEOS AT ONCE
    public function updateMultipleVideos(Request $request)
    {
        try {
            Log::info('Received multiple videos request:', $request->all());

            $request->validate([
                'videos' => 'required|array',
                'videos.*.id' => 'required|integer',
                'videos.*.url' => 'required|string',
                'videos.*.title' => 'nullable|string',
                'videos.*.thumbnail' => 'nullable|string',
                'videos.*.tags' => 'nullable|array',
                'videos.*.meta_title' => 'nullable|string',
                'videos.*.meta_description' => 'nullable|string'
            ]);

            $updatedVideos = [];
            $errors = [];

            foreach ($request->videos as $videoData) {
                try {
                    $video = Video::find($videoData['id']);
                    if ($video) {
                        $video->url = $videoData['url'];
                        
                        if (isset($videoData['title'])) {
                            $video->title = $videoData['title'];
                        }
                        if (isset($videoData['thumbnail'])) {
                            $video->thumbnail = $videoData['thumbnail'];
                        }
                        if (isset($videoData['tags'])) {
                            $video->tags = $videoData['tags'];
                        }
                        if (isset($videoData['meta_title'])) {
                            $video->meta_title = $videoData['meta_title'];
                        }
                        if (isset($videoData['meta_description'])) {
                            $video->meta_description = $videoData['meta_description'];
                        }
                        
                        $video->save();
                        $updatedVideos[] = $video;
                        
                        Log::info('Video updated:', ['id' => $videoData['id']]);
                    } else {
                        $errors[] = "Video ID {$videoData['id']} not found";
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error updating video {$videoData['id']}: " . $e->getMessage();
                }
            }

            $message = count($updatedVideos) . ' videos updated successfully';
            if (!empty($errors)) {
                $message .= '. Errors: ' . implode(', ', $errors);
            }

            return response()->json([
                'message' => $message,
                'data' => $updatedVideos,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            Log::error('Multiple videos update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update videos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET ALL VIDEOS
    public function getVideoUrls()
    {
        try {
            $videos = Video::orderBy('id', 'ASC')->get();
            Log::info('Fetched videos:', ['count' => $videos->count()]);
            return response()->json($videos);
        } catch (\Exception $e) {
            Log::error('Failed to fetch videos: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch videos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET SINGLE VIDEO
    public function show($id)
    {
        try {
            $video = Video::find($id);
            if (!$video) {
                return response()->json(['message' => 'Video not found'], 404);
            }
            return response()->json($video);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch video'], 500);
        }
    }

    // DELETE VIDEO
    public function destroy($id)
    {
        try {
            $video = Video::find($id);
            if (!$video) {
                return response()->json(['message' => 'Video not found'], 404);
            }
            $video->delete();
            Log::info('Video deleted:', ['id' => $id]);
            return response()->json(['message' => 'Video deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to delete video: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete video'], 500);
        }
    }
}