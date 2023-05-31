<?php


namespace App\Classes;


use App\Models\Accident;
use App\Models\Location;
use App\Models\Rating;
use Illuminate\Support\Collection;

class LocationHandler
{
    const ACCEPTABLE_PROXIMITY = 30;

    /*
     * Checks if there are locations for this incident closer than ACCEPTABLE_PROXIMITY and, if there are, verifies these locations
     */
    public function verificationCheck(Location $location): bool
    {
        $accident = Accident::find($location['accident_id']);
        $otherAccidentLocations = Location::whereAccidentId($accident->id)
            ->whereNull('note')
            ->where('id', '<>', $location['id'])
            ->get();

        foreach ($otherAccidentLocations as $otherLocation) {
            if ($this->getLocationsProximity($location, $otherLocation) <= self::ACCEPTABLE_PROXIMITY) {
                $this->verifyLocations([$location, $otherLocation]);
                $accident->update(['location_status' => Accident::LOCATION_STATUSES['located']]);

                return true;
            }
        }

        // if three of users specify different locations, then set accident's difficulty to hard (1)
        if (count($otherAccidentLocations) > 1) {
            $accident->update(['difficulty' => 1]);
        }

        return false;
    }

    public function verifyLocations(array|Collection $locations): void
    {
        foreach ($locations as $location) {
            $this->verifyLocation($location);
        }
    }

    public function verifyLocation(Location $location)
    {
        Rating::create([
            'value' => $location->accident->difficulty ? Rating::ComplicatedLocationAward :Rating::CommonLocationAward,
            'user_id' => $location->user->id,
            'location_id' => $location->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $location->verified_at = now();
        $location->save();
    }

    public function getLocationsProximity(Location $locationA, Location $locationB): float
    {
        $x = abs($locationA['long'] - $locationB['long']) * 111300 * cos(deg2rad(($locationA['lat'] + $locationB['lat']) / 2));
        $y = abs($locationA['lat'] - $locationB['lat']) * 111100;
        $proximity = sqrt(pow($x, 2) + pow($y, 2));

        return $proximity;
    }
}
