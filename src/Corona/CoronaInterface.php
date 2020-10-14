<?php declare(strict_types=1);

namespace App\Corona;

use App\Model\Result;
use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use Location\Coordinate;
use Location\Polygon;

interface CoronaInterface
{
    public function getResultForCoordinate(Coordinate $target): ?Result;
}