<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YouTubeService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
    }

    public function searchVideos($query)
    {
        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'q' => $query,
            'key' => $this->apiKey,
            'type' => 'video',
            'maxResults' => 10,
        ]);

        return $response->json();
    }

    public function getVideoDetails($videoId)
    {
        $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
            'part' => 'snippet,contentDetails,statistics',
            'id' => $videoId,
            'key' => $this->apiKey,
        ]);

        return $response->json();
    }
}
