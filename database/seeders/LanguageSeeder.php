<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Media;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::truncate();
        $languages = [
            [
                'title' => 'English',
                'name' => 'en',
            ], [
                'title' => 'Arabic',
                'name' => 'ar',
            ],
            [
                'title' => 'Bangla',
                'name' => 'bn',
            ]
        ];

        foreach ($languages as $language) {
            $media = Media::factory()->create([
                'src' => 'flags/' . $language['name'] . '.jpg',
            ]);
            Language::create([
                'title' => $language['title'],
                'name' => $language['name'],
                'thumbnail_id' => $media->id
            ]);
        }
    }
}
