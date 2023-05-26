<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Votable
{
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function vote($value): Model
    {
        return $this->votes()->create([
            'user_id' => auth()->id(),
            'value' => $value,
        ]);
    }

    public function unvote()
    {
        $attributes = ['user_id' => auth()->id()];
        $this->votes()->where($attributes)->get()->each->delete();
    }
}
