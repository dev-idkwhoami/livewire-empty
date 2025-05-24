<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostCommentSeeder extends Seeder
{
    /**
     * The categories for posts.
     *
     * @var array
     */
    protected $categories = [
        'video games',
        'technology',
        'animals',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have users, if not create some
        if (User::count() === 0) {
            User::factory(5)->create();
        }

        $users = User::all();

        // For each category, create posts and comments
        foreach ($this->categories as $category) {
            $this->createPostsAndCommentsForCategory($category, $users);
        }
    }

    /**
     * Create posts and comments for a specific category.
     *
     * @param  string  $category
     * @param  \Illuminate\Database\Eloquent\Collection  $users
     * @return void
     */
    protected function createPostsAndCommentsForCategory($category, $users)
    {
        // Get data from ChatGPT
        $data = $this->getDataFromChatGPT($category);

        if (empty($data)) {
            Log::warning("Failed to get data from ChatGPT for category: {$category}");

            return;
        }

        // Create posts and comments
        foreach ($data['posts'] as $postData) {
            // Create post
            $post = Post::create([
                'user_id' => $users->random()->id,
                'title' => $postData['title'],
                'content' => $postData['content'],
                'category' => $category,
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            // Create comments for this post
            foreach ($postData['comments'] as $commentData) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'content' => $commentData['content'],
                    'created_at' => $post->created_at->addDays(rand(1, 3)),
                ]);
            }
        }
    }

    /**
     * Get data from ChatGPT API.
     *
     * @param  string  $category
     * @return array|null
     */
    protected function getDataFromChatGPT($category)
    {
        // This is a simulation of ChatGPT API call
        // In a real application, you would use your API key and make a real API call

        // For demonstration purposes, we'll create mock data
        // In a real application, replace this with actual API call

        $prompt = "Generate 3 forum posts about {$category} with 2-3 comments each. Format the response as a JSON object with the following structure:
        {
            \"posts\": [
                {
                    \"title\": \"Post title\",
                    \"content\": \"Post content\",
                    \"comments\": [
                        {
                            \"content\": \"Comment content\"
                        }
                    ]
                }
            ]
        }";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.config('openai.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant that generates forum posts and comments.',
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

            $data = $response->json();

            return json_decode($data['choices'][0]['message']['content'], true);
        } catch (\Exception $e) {
            Log::error('Error fetching data from ChatGPT: '.$e->getMessage());
            Log::info("Using mock data as fallback for category: {$category}");

            return $this->getMockDataForCategory($category);
        }
    }

    /**
     * Get mock data for a specific category.
     *
     * @param  string  $category
     * @return array
     */
    protected function getMockDataForCategory($category)
    {
        $mockData = [
            'video games' => [
                'posts' => [
                    [
                        'title' => 'What\'s your favorite game of all time?',
                        'content' => 'I\'ve been gaming for over 20 years and I still think The Witcher 3 is the best game ever made. The story, the characters, the world - everything is just perfect. What\'s your favorite game and why?',
                        'comments' => [
                            ['content' => 'For me it\'s definitely Skyrim. I\'ve spent thousands of hours exploring that world and I still find new things.'],
                            ['content' => 'The Last of Us changed my perspective on what video game storytelling could be. Nothing has topped it for me yet.'],
                            ['content' => 'I\'m old school - Super Mario 64 was revolutionary and still holds up today!'],
                        ],
                    ],
                    [
                        'title' => 'PS5 vs Xbox Series X - Which is better?',
                        'content' => 'I\'m looking to upgrade my console and I\'m torn between the PS5 and Xbox Series X. What are your thoughts on which one is better in terms of performance, game library, and overall value?',
                        'comments' => [
                            ['content' => 'PS5 has better exclusives in my opinion, but Game Pass on Xbox is incredible value.'],
                            ['content' => 'I went with PS5 for the DualSense controller - the haptic feedback is a game changer.'],
                        ],
                    ],
                    [
                        'title' => 'Is Elden Ring too difficult?',
                        'content' => 'I\'ve been trying to get into Elden Ring but I find it incredibly difficult. I\'ve never played a Souls game before. Should I keep trying or is it just not for me?',
                        'comments' => [
                            ['content' => 'It has a steep learning curve but once it clicks, it\'s incredibly rewarding. Don\'t give up!'],
                            ['content' => 'Try using summons and magic - they make the game much more accessible.'],
                            ['content' => 'No shame in looking up guides or builds online to help you get started.'],
                        ],
                    ],
                ],
            ],
            'technology' => [
                'posts' => [
                    [
                        'title' => 'What\'s your take on AI and the future of work?',
                        'content' => 'With the rapid advancement of AI tools like ChatGPT, I\'m curious about how people think this will impact jobs and the workforce in the next 5-10 years. Are we headed for massive disruption or will it just be another tool?',
                        'comments' => [
                            ['content' => 'I think it\'ll be like previous technological revolutions - some jobs will disappear, new ones will be created, and most will just change.'],
                            ['content' => 'As a programmer, I\'m already using AI to help with coding. It makes me more productive rather than replacing me.'],
                            ['content' => 'I\'m concerned about the pace of change - previous revolutions happened over decades, but AI is moving much faster.'],
                        ],
                    ],
                    [
                        'title' => 'Best laptop for programming in 2025?',
                        'content' => 'I\'m in the market for a new laptop primarily for coding. Budget is around $1500. I mainly do web development and some light machine learning work. Any recommendations?',
                        'comments' => [
                            ['content' => 'MacBook Air with M2 chip is hard to beat for that price range - great performance and battery life.'],
                            ['content' => 'Dell XPS is a great Windows alternative with excellent build quality and screen.'],
                        ],
                    ],
                    [
                        'title' => 'Thoughts on the metaverse - hype or future?',
                        'content' => 'There\'s been a lot of talk about the metaverse over the past few years, but adoption seems slow. Do you think it\'s just hype or will it eventually become mainstream?',
                        'comments' => [
                            ['content' => 'I think it\'s a solution looking for a problem right now. The technology isn\'t quite there yet for mass adoption.'],
                            ['content' => 'Gaming will lead the way - once we have more accessible VR/AR hardware, other applications will follow.'],
                            ['content' => 'The business applications for training and collaboration could be huge once the technology matures.'],
                        ],
                    ],
                ],
            ],
            'animals' => [
                'posts' => [
                    [
                        'title' => 'What\'s the most intelligent animal you\'ve encountered?',
                        'content' => 'I recently watched a documentary about octopuses and was blown away by their problem-solving abilities. It got me wondering what other animals people have been surprised by in terms of intelligence?',
                        'comments' => [
                            ['content' => 'Crows are incredibly smart - they can use tools, recognize human faces, and even hold grudges!'],
                            ['content' => 'My border collie constantly surprises me with how she learns new things and seems to understand complex commands.'],
                            ['content' => 'Elephants have amazing social intelligence and emotional depth - they even mourn their dead.'],
                        ],
                    ],
                    [
                        'title' => 'Best low-maintenance pets for apartment living?',
                        'content' => 'I live in a small apartment and work long hours, but I\'d really like to have a pet for companionship. What are some good options that don\'t require tons of space or constant attention?',
                        'comments' => [
                            ['content' => 'Cats are perfect for apartments - independent but still affectionate when they want to be.'],
                            ['content' => 'Fish tanks can be very relaxing to watch and don\'t require daily walks or attention.'],
                        ],
                    ],
                    [
                        'title' => 'Ethical concerns about zoos - what\'s your opinion?',
                        'content' => 'I\'ve always enjoyed visiting zoos, but lately I\'ve been thinking more about the ethics of keeping animals in captivity. Are modern zoos beneficial for conservation or are they problematic?',
                        'comments' => [
                            ['content' => 'I think it depends on the zoo - the good ones prioritize animal welfare and contribute to conservation efforts.'],
                            ['content' => 'I prefer wildlife sanctuaries that focus on rehabilitation and only keep animals that can\'t survive in the wild.'],
                            ['content' => 'Zoos play an important role in education and getting people to care about endangered species.'],
                        ],
                    ],
                ],
            ],
        ];

        return $mockData[$category] ?? [];
    }
}
