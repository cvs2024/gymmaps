<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Seeder;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sports = [
            ['name' => 'Fitness', 'slug' => 'fitness'],
            ['name' => 'Personal trainer', 'slug' => 'personal-trainer'],
            ['name' => 'Yoga', 'slug' => 'yoga'],
            ['name' => 'Boksen', 'slug' => 'boksen'],
            ['name' => 'CrossFit', 'slug' => 'crossfit'],
            ['name' => 'Tennis', 'slug' => 'tennis'],
            ['name' => 'Squash', 'slug' => 'squash'],
            ['name' => 'Padel', 'slug' => 'padel'],
        ];

        foreach ($sports as $sport) {
            Sport::query()->updateOrCreate(
                ['slug' => $sport['slug']],
                ['name' => $sport['name']]
            );
        }
    }
}
