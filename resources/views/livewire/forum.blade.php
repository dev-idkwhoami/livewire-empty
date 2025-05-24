<div class="max-w-5xl mx-auto px-4 py-8">
    <!-- Forum Header -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-400 to-purple-500 bg-clip-text text-transparent">Forum Discussions</h1>
        <div class="flex items-center space-x-4">
            <div class="bg-indigo-600/10 dark:bg-indigo-500/20 px-3 py-1 rounded-full">
                <span class="text-indigo-600 dark:text-indigo-300 font-medium">{{ count($this->posts) }} Posts</span>
            </div>
            <button
                wire:click="generatePost"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Generate Post
                <flux:icon.loading wire:loading wire:target="generatePost" class="ml-2 h-4 w-4" />
            </button>
        </div>
    </div>

    <!-- Posts List -->
    <div class="space-y-6" wire:poll.10s>
        <!-- Loading Indicator -->
        <div wire:loading wire:target="posts" x-transition.opacity class="flex justify-center py-4">
            <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm rounded-md text-indigo-500 bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500 dark:text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Refreshing...
            </div>
        </div>

        @foreach($this->posts as $post)
            <div
                :key="$post->id"
                class="bg-white dark:bg-zinc-900/50 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 border border-zinc-200/60 dark:border-zinc-700/80 overflow-hidden backdrop-blur-sm"
                x-data="{ open: false }"
            >
                <!-- Post Header -->
                <div class="p-6 pb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-zinc-800 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ $post->title }}
                        </h3>
                        <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded-full">
                            {{ $post->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed mb-4">{{ $post->content }}</p>

                    <!-- Post Footer -->
                    <div class="flex items-center justify-between pt-2 border-t border-zinc-100 dark:border-zinc-800">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-medium text-sm">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $post->author->name }}</span>
                        </div>

                        <!-- Comments Button -->
                        <button
                            class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                            @click="open = !open"
                        >
                            <span>{{ $post->comments_count }} Comments</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Comments Section -->
                <div
                    x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-4"
                    class="bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-700"
                >
                    <div class="p-6 space-y-4">
                        <div class="flex justify-end mb-2">
                            <button
                                wire:click="generateComment({{ $post->id }})"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-medium rounded-md transition-colors duration-150 ease-in-out"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Generate Comment
                            </button>
                        </div>

                        @if(count($post->comments) > 0)
                            @foreach($post->comments as $comment)
                                <div class="bg-white dark:bg-zinc-900/70 p-4 rounded-lg border border-zinc-200/80 dark:border-zinc-700/80 hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-colors">
                                    <p class="text-zinc-700 dark:text-zinc-300 text-sm mb-3">{{ $comment->content }}</p>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center text-white text-xs">
                                            {{ substr($comment->author->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs text-zinc-500 dark:text-zinc-400 ml-2">
                                            <span class="font-medium">{{ $comment->author->name }}</span> • {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-zinc-300 dark:text-zinc-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <p class="text-zinc-500 dark:text-zinc-400 text-sm">No comments yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $this->posts->links() }}
    </div>
</div>
