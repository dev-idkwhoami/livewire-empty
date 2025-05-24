<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Forum extends Component
{
    use WithPagination;

    public $semantic = false;
    public $search = '';

    protected $listeners = [
        'reloadPosts' => '$refresh',
    ];

    #[Locked]
    protected $categories = [
        'video games',
        'technology',
        'animals',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function posts(): LengthAwarePaginator
    {
        $baseQuery = Post::query()
            ->with([
                'author',
                'comments',
                'comments.author',
            ])
            ->withCount([
                'comments',
            ])
            ->latest();

        if(!empty($this->search)) {
            if (!$this->semantic) {
                $baseQuery->whereAny([
                    'title',
                    'content',
                    'category',
                ], 'ILIKE', "%{$this->search}%");
            }

            if($this->semantic) {

            }
        }

        return $baseQuery
            ->paginate(2);
    }

    public function generatePost()
    {
        // Get a random category
        $category = $this->categories[array_rand($this->categories)];

        // Get data from ChatGPT or use mock data
        $postData = $this->getPostDataFromChatGPT($category);

        if (empty($postData)) {
            session()->flash('error', 'Failed to generate post. Please try again.');

            return;
        }

        // Create the post
        Post::create([
            'user_id' => Auth::id(),
            'title' => $postData['title'],
            'content' => $postData['content'],
            'category' => $category,
        ]);

        $this->dispatch('reloadPosts')->self();
    }

    public function generateComment($postId)
    {
        // Find the post
        $post = Post::query()->findOrFail($postId);

        if (!$post) {
            session()->flash('error', 'Post not found.');

            return;
        }

        // Get data from ChatGPT or use mock data
        $commentData = $this->getCommentDataFromChatGPT($post->title, $post->content);

        if (empty($commentData)) {
            session()->flash('error', 'Failed to generate comment. Please try again.');

            return;
        }

        // Create the comment
        Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'content' => $commentData['content'],
        ]);

        $this->dispatch('reloadPosts')->self();
    }

    protected function getPostDataFromChatGPT($category)
    {
        $prompt = "Generate a forum post about {$category}. Format the response as a JSON object with the following structure:
        {
            \"title\": \"Post title\",
            \"content\": \"Post content\"
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
                        'content' => 'You are a helpful assistant that generates forum posts.',
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

            $data = $response->json();

            return json_decode($data['choices'][0]['message']['content'], true);
        } catch (\Exception $e) {
            Log::error('Error fetching post data from ChatGPT: '.$e->getMessage());
            Log::info('Using mock data as fallback for post');

            return $this->getMockPostData($category);
        }
    }

    protected function getCommentDataFromChatGPT($postTitle, $postContent)
    {
        $prompt = "Generate a comment for a forum post with the title \"{$postTitle}\" and content \"{$postContent}\". Format the response as a JSON object with the following structure:
        {
            \"content\": \"Comment content\"
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
                        'content' => 'You are a helpful assistant that generates forum comments.',
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

            $data = $response->json();

            return json_decode($data['choices'][0]['message']['content'], true);
        } catch (\Exception $e) {
            Log::error('Error fetching comment data from ChatGPT: '.$e->getMessage());
            Log::info('Using mock data as fallback for comment');

            return $this->getMockCommentData();
        }
    }

    protected function getMockPostData($category)
    {
        $mockPosts = [
            'video games' => [
                [
                    'title' => 'Just finished Elden Ring and I\'m blown away',
                    'content' => 'After 80+ hours, I finally beat the final boss. What an incredible journey! The world design, the challenging combat, the variety of builds - everything was top notch. What did you all think of the game?',
                ],
                [
                    'title' => 'Nintendo Switch 2 rumors - what features do you want?',
                    'content' => 'With rumors swirling about the Switch 2 coming next year, I\'m curious what features everyone is hoping for. Personally, I\'d love to see 4K output when docked, better battery life, and backward compatibility with the current Switch library.',
                ],
            ],
            'technology' => [
                [
                    'title' => 'Is cloud gaming the future?',
                    'content' => 'With services like GeForce Now, Xbox Cloud Gaming, and Amazon Luna getting better, I\'m wondering if traditional console/PC gaming will eventually be replaced by cloud gaming. What are your thoughts on the future of this technology?',
                ],
                [
                    'title' => 'Best programming language for beginners in 2025',
                    'content' => 'I\'m looking to learn programming and wondering what language would be best to start with in 2025. Python seems popular, but I\'ve also heard good things about JavaScript and Rust. Any recommendations?',
                ],
            ],
            'animals' => [
                [
                    'title' => 'My cat keeps knocking things off shelves - help!',
                    'content' => 'My 2-year-old tabby has developed a habit of systematically knocking everything off every shelf he can reach. I\'ve tried deterrent sprays, double-sided tape, and even motion-activated air sprayers, but nothing works. Any advice from fellow cat owners?',
                ],
                [
                    'title' => 'Most fascinating animal facts you know',
                    'content' => 'I recently learned that octopuses have three hearts and blue blood, which blew my mind. What are some of the most interesting animal facts you know?',
                ],
            ],
        ];

        $postsForCategory = $mockPosts[$category] ?? $mockPosts['technology'];

        return $postsForCategory[array_rand($postsForCategory)];
    }

    protected function getMockCommentData()
    {
        $mockComments = [
            ['content' => 'This is a really interesting perspective! I hadn\'t thought about it that way before.'],
            ['content' => 'I completely agree with your points. Especially about the third one - that\'s something I\'ve experienced myself.'],
            ['content' => 'Have you considered looking at it from another angle? I found that approaching this differently helped me a lot.'],
            ['content' => 'Thanks for sharing this! I\'ve been struggling with the same issue and your post gave me some ideas to try.'],
            ['content' => 'I respectfully disagree. In my experience, the opposite has been true. But I appreciate you sharing your thoughts!'],
            ['content' => 'This is exactly what I needed to read today. Perfect timing!'],
            ['content' => 'Do you have any resources or links where I could learn more about this topic?'],
            ['content' => 'I had a similar experience last year. It\'s nice to know I\'m not the only one!'],
        ];

        return $mockComments[array_rand($mockComments)];
    }

    public function render(): View
    {
        return view('livewire.forum');
    }
}
