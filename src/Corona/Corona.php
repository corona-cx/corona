<?php


namespace App\Corona;


use App\Model\Result;
use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use Location\Coordinate;
use Location\Polygon;

class Corona implements CoronaInterface
{
    public function getResultForCoordinate(Coordinate $target): Result
    {
        $content = file_get_contents('https://services7.arcgis.com/mOBPykOjAyBO2ZKk/arcgis/rest/services/RKI_Landkreisdaten/FeatureServer/0/query?where=1%3D1&objectIds=&time=&geometry=&geometryType=esriGeometryPolygon&inSR=&spatialRel=esriSpatialRelIntersects&resultType=none&distance=0.0&units=esriSRUnit_Meter&returnGeodetic=false&outFields=*&returnGeometry=true&returnCentroid=false&featureEncoding=esriDefault&multipatchOption=none&maxAllowableOffset=&geometryPrecision=&outSR=&datumTransformation=&applyVCSProjection=false&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnExtentOnly=false&returnQueryGeometry=true&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&returnZ=false&returnM=false&returnExceededLimitFeatures=true&quantizationParameters=&sqlFormat=none&f=pgeojson&token=');

        $json = json_decode($content);

        /** @var FeatureCollection $featureCollection */
        $featureCollection = \GeoJson\GeoJson::jsonUnserialize($json);

        $iterator = $featureCollection->getIterator();

        /** @var Feature $feature */
        while ($feature = $iterator->current()) {
            $coordList = $feature->getGeometry()->getCoordinates()[0];
            $geofence = new Polygon();

            foreach ($coordList as $coord) {
                $geofence->addPoint(new Coordinate($coord[1], $coord[0]));
            }

            $match = $geofence->contains($target);

            if ($match) {
                return FeatureResultConverter::convert($feature);
            }

            $iterator->next();
        }
    }
}