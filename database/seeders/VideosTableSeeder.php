<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VideosTableSeeder extends Seeder
{
    public function run(): void
    {
        // First, ensure the table has all required columns
        Schema::disableForeignKeyConstraints();
        DB::table('videos')->truncate();
        Schema::enableForeignKeyConstraints();

        $videos = [
            [
                'id' => 1,
                'url' => 'https://youtube.com/embed/uk-study-video',
                'title' => 'Study in UK',
                'thumbnail' => 'Study in UK',
                'tags' => json_encode(['#studyabroad', '#UK', '#education']),
                'meta_title' => 'Study in UK - Global Routeway',
                'meta_description' => 'Complete guide for studying in UK',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'url' => 'https://youtube.com/embed/uk-student-visa',
                'title' => 'UK Student Visa Guide',
                'thumbnail' => 'UK Visa',
                'tags' => json_encode(['#visa', '#education', '#students']),
                'meta_title' => 'UK Student Visa - Global Routeway',
                'meta_description' => 'Complete guide for UK student visa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'url' => 'https://youtube.com/embed/nz-study-program',
                'title' => 'Study in New Zealand',
                'thumbnail' => 'Study in NZ',
                'tags' => json_encode(['#studyabroad', '#NZ', '#scholarship']),
                'meta_title' => 'Study in New Zealand - Global Routeway',
                'meta_description' => 'Complete guide for studying in New Zealand',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($videos as $video) {
            DB::table('videos')->insert($video);
        }

        $this->command->info('Videos table seeded successfully!');
    }
}