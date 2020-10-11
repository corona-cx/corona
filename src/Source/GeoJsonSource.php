<?php declare(strict_types=1);

namespace App\Source;

use GeoJson\Feature\FeatureCollection;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class GeoJsonSource
{
    const KEY = 'corona_geojson';
    const NAMESPACE = 'criticalmass_corona';
    const TTL = 60 * 60 * 24;

    protected AbstractAdapter $adapter;

    public function __construct()
    {
        $this->adapter = new FilesystemAdapter(self::NAMESPACE, self::TTL);
    }

    public function getFeatureCollection(): FeatureCollection
    {
        return $this->adapter->get(self::KEY, function() {
            $content = file_get_contents('https://services7.arcgis.com/mOBPykOjAyBO2ZKk/arcgis/rest/services/RKI_Landkreisdaten/FeatureServer/0/query?where=1%3D1&objectIds=&time=&geometry=&geometryType=esriGeometryPolygon&inSR=&spatialRel=esriSpatialRelIntersects&resultType=none&distance=0.0&units=esriSRUnit_Meter&returnGeodetic=false&outFields=*&returnGeometry=true&returnCentroid=false&featureEncoding=esriDefault&multipatchOption=none&maxAllowableOffset=&geometryPrecision=&outSR=&datumTransformation=&applyVCSProjection=false&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnExtentOnly=false&returnQueryGeometry=true&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&returnZ=false&returnM=false&returnExceededLimitFeatures=true&quantizationParameters=&sqlFormat=none&f=pgeojson&token=');

            $json = json_decode($content);

            /** @var FeatureCollection $featureCollection */
            return \GeoJson\GeoJson::jsonUnserialize($json);
        });
    }
}
