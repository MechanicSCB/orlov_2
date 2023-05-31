<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accident extends Model
{
    use HasFactory;

    const LOCATION_STATUSES = [
        'no needs localization' => 0,
        'needs localization' => 1,
        'located' => 2,
    ];

    protected $guarded = [];

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function reservedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }
}
