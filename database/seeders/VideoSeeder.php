<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Video;

class VideoSeeder extends Seeder
{
    public function run()
    {
        Video::create([
            'title' => 'Sample Video 1',
            'description' => 'Description for Sample Video 1',
            'youtube_url' => 'https://www.youtube.com/watch?v=XbGs_qK2PQA&list=RDEMASZzAB4N6PSfsOwzAhxYyQ&index=14',
            'user_id' => 1,
        ]);

        Video::create([
            'title' => 'Sample Video 2',
            'description' => 'Description for Sample Video 2',
            'youtube_url' => 'https://www.youtube.com/watch?v=XbGs_qK2PQA&list=RDEMASZzAB4N6PSfsOwzAhxYyQ&index=14',
            'user_id' => 1,
        ]);
    }
}

