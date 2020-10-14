<?php declare(strict_types=1);

namespace App\Corona;

use App\Model\Result;
use App\Source\GeoJsonSource;
use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use Location\Coordinate;
use Location\Polygon;

class Corona implements CoronaInterface
{
    protected GeoJsonSource $geoJsonSource;

    public function __construct(GeoJsonSource $geoJsonSource)
    {
        $this->geoJsonSource = $geoJsonSource;
    }
    public function getResultForCoordinate(Coordinate $target): ?Result
    {
        $featureCollection = $this->geoJsonSource->getFeatureCollection();

        $iterator = $featureCollection->getIterator();

        /** @var Feature $feature */
        while ($feature = $iterator->current()) {
            $areaList = $feature->getGeometry()->getCoordinates();
            $geofence = new Polygon();

            foreach ($areaList as $coordList) {
                if (1 === count($coordList) && 2 !== count($coordList[0])) { // @TODO fix this nested lists
                    $coordList = array_pop($coordList);
                }

                foreach ($coordList as $coord) {
                    $geofence->addPoint(new Coordinate($coord[1], $coord[0]));
                }

                $match = $geofence->contains($target);

                if ($match) {
                    return FeatureResultConverter::convert($feature);
                }
            }

            $iterator->next();
        }

        return null;
    }
}