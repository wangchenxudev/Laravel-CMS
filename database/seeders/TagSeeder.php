<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * The demo tag vocabulary managed by admins.
     *
     * @var list<string>
     */
    private array $tags = [
        'Laravel',
        'PHP',
        'Frontend',
        'Backend',
        'DevOps',
        'Tutorial',
        'News',
        'Opinion',
        'Security',
        'Performance',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->tags as $name) {
            Tag::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
