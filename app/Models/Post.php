<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Post extends Model
{
    use Votable, HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post['slug'] ??= $post->title;
        });
    }

    // Relations
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

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Accessors & Mutators

    /**
     * Set post's slug attribute
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: function (string $value) {
                $slug = $original = Str::slug($value);
                $count = 2;

                while (static::query()->where('slug', $slug)->exists()) {
                    $slug = "{$original}-" . $count++;
                }

                return $slug;
            },
        );
    }

    /**
     * Get the post's published_at datetime with Moscow timezone
     */
    protected function publishedAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::make($value)->setTimezone('Europe/Moscow')->format('d.m.Y, в H:i'),
        );
    }

}
