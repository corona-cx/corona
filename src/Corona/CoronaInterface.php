<?php declare(strict_types=1);

namespace App\Corona;

use App\Entity\Data;
use Location\Coordinate;

interface CoronaInterface
{
    public function getResultForCoordinate(Coordinate $target): ?Data;
}
