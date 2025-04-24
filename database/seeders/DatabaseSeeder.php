<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        $tags = Tag::factory(15)
            ->has(
                Post::factory(random_int(1, 8))
                    ->recycle($users),
                'posts'
            )
            ->create();

        $tagIds = $tags->pluck('id');

        Post::all()->each(fn(Post $post) => $post->tags()->attach(fake()->randomElements($tagIds, random_int(1, 3))));

        User::factory()->create([
            'name' => 'Developer',
            'email' => 'test@livewire.test',
        ]);
    }
}
