<?php declare(strict_types=1);

namespace App\Source;

use App\Corona\CoronaInterface;
use App\Entity\Data;
use Location\Coordinate;

class ResultSource
{
    protected CoronaInterface $corona;

    public function __construct(CoronaInterface $corona)
    {
        $this->corona = $corona;
    }

    public function getResultForCoordinate(Coordinate $target): ?Data
    {
        return $this->corona->getResultForCoordinate($target);
    }
}
