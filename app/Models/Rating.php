<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    const RegistrationInitialAward = 10;
    const CommonLocationAward = 10;
    const ComplicatedLocationAward = 30;
    const VoteAward = 1; // rating points assigned for one vote point
    const ComplicatedAllowedRating = 70;

    protected $guarded = [];
}
