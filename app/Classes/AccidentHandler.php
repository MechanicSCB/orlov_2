<?php


namespace App\Classes;


use App\Models\Accident;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccidentHandler
{
    const RESERVE_MINUTES = 30; // minutes while video reserved by user to define location
    const RATING_FOR_ACCESS_TO_DIFFICULT = 200;

    public function getAccidentToDefineLocation(string $complicated = null, bool $noFavoriteRegionsAccidents = false): Accident|Model|null
    {
        $difficulty = $complicated === 'complicated' ? 1 : 0;

        $reservedByAuthAccident = Accident::whereReservedBy(auth()->user()->id)->first();

        if (@$reservedByAuthAccident->difficulty === $difficulty) {
            return $reservedByAuthAccident;
        }

        $authAccidentsIds = auth()->user()->locations()->pluck('accident_id');

        $otherActiveUsersIds = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('user_id', '!=', auth()->user()->id)
            ->where('last_activity', '>', time() - self::RESERVE_MINUTES * 60)
            ->pluck('user_id')
            ->toArray();

        $accident = Accident::query();

        if (count($favoriteRegions = auth()->user()->favoriteRegions) && !$noFavoriteRegionsAccidents) {
            $accident->whereIn('region_id', $favoriteRegions->pluck('id'));
        }

        $accident = $accident->whereNotIn('id', $authAccidentsIds)
            ->where('location_status', Accident::LOCATION_STATUSES['needs localization'])
            ->where('difficulty', $difficulty)
            ->where(fn($query) => $query
                ->whereNotIn('reserved_by', $otherActiveUsersIds)
                ->orWhere('reserved_by', null)
            )
            ->orderByDesc('location_status')
            ->orderBy('id')
            ->first();

        // Get random region accident if there is no accidents in user's favorite regions
        // TODO inform the user about the absence of accident in his favorite regions
        $accident ??= $this->getAccidentToDefineLocation(0,$noFavoriteRegionsAccidents = true);

        $user = auth()->user();
        $user->reservedAccident?->update(['reserved_by' => null]);

        $accident['reserved_by'] = $user->id;
        $accident->save();

        return $accident;
    }

    public function getAccidentToDefineLocationOld(bool $noFavoriteRegionsAccidents = false): Accident|Model|null
    {
        if ($reservedByAuthAccident = Accident::whereReservedBy(auth()->user()->id)->first()) {
            return $reservedByAuthAccident;
        }

        $authAccidentsIds = auth()->user()->locations()->pluck('accident_id');

        $otherActiveUsersIds = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('user_id', '!=', auth()->user()->id)
            ->where('last_activity', '>', time() - self::RESERVE_MINUTES * 60)
            ->pluck('user_id')
            ->toArray();

        $accident = Accident::query();

        if (count($favoriteRegions = auth()->user()->favoriteRegions) && !$noFavoriteRegionsAccidents) {
            $accident->whereIn('region_id', $favoriteRegions->pluck('id'));
        }

        if (auth()->user()->ratings()->sum('value') >= self::RATING_FOR_ACCESS_TO_DIFFICULT) {
            $accident->whereIn('location_status', [1, 3]);
        } else {
            $accident->where('location_status', 1);
        }

        $accident = $accident->whereNotIn('id', $authAccidentsIds)
            ->where('difficulty', 0)
            ->where(fn($query) => $query
                ->whereNotIn('reserved_by', $otherActiveUsersIds)
                ->orWhere('reserved_by', null)
            )
            ->orderByDesc('location_status')
            ->orderBy('id')
            ->first();

        // Get random region accident if there is no accidents in user's favorite regions
        // TODO inform the user about the absence of accident in his favorite regions
        $accident ??= $this->getAccidentToDefineLocation($noFavoriteRegionsAccidents = true);

        $accident['reserved_by'] = auth()->user()->id;

        $accident->save();

        return $accident;
    }
}
