<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Quero\Traits\HasEmbeddings;

class Post extends Model
{
    use HasEmbeddings;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
    ];

    /**
     * Get the user that owns the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the comments for the post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return string[]
     */
    public static function embeddableProperties(): array
    {
        return [
            'title' => 1,
            'content' => 0.5,
            'category' => 0.1,
            'author' => 0.25,
        ];
    }
}
