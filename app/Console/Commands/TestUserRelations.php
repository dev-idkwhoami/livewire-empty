<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestUserRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user-relations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test User model relations to Posts and Comments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find a user with posts
        $user = User::whereHas('posts')->first();

        if (!$user) {
            // If no user with posts is found, get the first user
            $user = User::first();

            if (!$user) {
                $this->error('No users found');
                return 1;
            }

            $this->warn('No users with posts found. Using the first user instead.');
        }

        $this->info("User: {$user->name}");
        $this->info("Posts count: {$user->posts->count()}");
        $this->info("Comments count: {$user->comments->count()}");

        $this->info("\nPosts:");
        foreach ($user->posts as $post) {
            $this->info("- ID: {$post->id}, Title: {$post->title}");
            $this->info("  Comments count: {$post->comments->count()}");

            if ($post->comments->count() > 0) {
                $this->info("  Comments:");
                foreach ($post->comments as $comment) {
                    $this->info("  - ID: {$comment->id}, Content: " . substr($comment->content, 0, 50) . "...");
                }
            }
        }

        return 0;
    }
}
