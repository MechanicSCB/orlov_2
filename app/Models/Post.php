<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Post extends Model
{
    use Votable, HasFactory;

    // protected $casts = [
    //     'published_at' => 'datetime:d.m.Y, в H:i',
    // ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->where('parent_id', null)
            ->with('user', 'comments')
            ->latest('published_at');
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the post's published_at datetime with Moscow timezone
     */
    protected function publishedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::make($value)->setTimezone('Europe/Moscow')->format('d.m.Y, в H:i'),
        );
    }

}
