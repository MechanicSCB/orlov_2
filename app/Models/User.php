<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected $with= [
        'votes'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->ratings()->create([
                'value' => Rating::RegistrationInitialAward,
                'user_id' => $user->id,
                'created_at' => $user->created_at,
                'updated_at' => $user->created_at,
            ]);
        });
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function votedPosts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'votable', 'votes');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function monthRatings(): HasMany
    {
        return $this->hasMany(Rating::class)->where('created_at','>', now()->startOfMonth());
    }

    public function weekRatings(): HasMany
    {
        return $this->hasMany(Rating::class)->where('created_at','>', now()->startOfWeek());
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function latestLocations(): HasMany
    {
        return $this->hasMany(Location::class)->latest();
    }

    public function favoriteRegions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'favorite_region_user', 'user_id', 'favorite_region_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function latestComments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function reservedAccident(): HasOne
    {
        return $this->hasOne(Accident::class, 'reserved_by');
    }
}
