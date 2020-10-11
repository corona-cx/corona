<?php declare(strict_types=1);

namespace App\Corona;

use App\Model\Result;
use GeoJson\Feature\Feature;

class FeatureResultConverter
{
    private function __construct()
    {

    }

    public static function convert(Feature $feature): Result
    {
        $result = new Result();

        $properties = $feature->getProperties();

        $result->setDeathRate($properties['death_rate'])
            ->setCases($properties['cases'])
            ->setDeaths($properties['deaths'])
            ->setCasesPer100K($properties['cases_per_100k'])
            ->setCasesPerPopulation($properties['cases_per_population'])
            ->setLastUpdate(new \DateTime(str_replace(' Uhr', '', $properties['last_update'])))
            ->setCases7Per100K($properties['cases7_per_100k'])
            ->setRecovered($properties['recovered'])
            ->setPopulation($properties['EWZ_BL'])
            ->setCases7BlPer100K($properties['cases7_bl_per_100k']);

        return $result;
    }
}
