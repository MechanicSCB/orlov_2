<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Comment extends Model
{
    use Votable, HasFactory;

    protected $guarded = [];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with('comments', 'user')
            ->latest();
    }

    public function loadVotesRecursively()
    {
        $this->loadSum('votes', 'value');

        foreach ($this['comments'] as $comment) {
            $comment->loadVotesRecursively();
        }
    }

    /**
     * Get the comment's published_at datetime with Moscow timezone
     */
    protected function publishedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::make($value)->setTimezone('Europe/Moscow')->format('d.m.Y, в H:i'),
        );
    }
}
