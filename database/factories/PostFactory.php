<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'content' => $this->faker->paragraphs(2, true),
            'user_id' => $this->getRandomRecycledModel(User::class),
        ];
    }
}
