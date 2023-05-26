<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Vote extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function votable(): MorphTo
    {
        return $this->morphTo(); //return $this->morphTo(__FUNCTION__, 'imageable_type', 'imageable_id');
    }
}
