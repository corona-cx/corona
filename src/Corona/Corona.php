<?php declare(strict_types=1);

namespace App\Corona;

use App\Entity\Area;
use App\Entity\Data;
use Doctrine\Persistence\ManagerRegistry;
use Location\Coordinate;
use Location\Polygon;

class Corona implements CoronaInterface
{
    protected ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function getResultForCoordinate(Coordinate $target): ?Data
    {
        $areaList = $this->managerRegistry->getRepository(Area::class)->findAll();
        $targetArea = null;

        /** @var Area $area */
        foreach ($areaList as $area) {
            $coordList = json_decode($area->getShape());

            if ($this->shapeContains($coordList, $target)) {
                $targetArea = $area;
                break;
            }
        }

        if ($targetArea) {
            return $this->managerRegistry->getRepository(Data::class)->findLatestForArea($area);
        }

        return null;
    }

    protected function shapeContains(array $coordList, Coordinate $target): bool
    {
        $firstElement = $coordList[0];

        if (2 === count($firstElement) && is_float($firstElement[0]) && is_float($firstElement[1])) {
            $geofence = new Polygon();

            foreach ($coordList as $coord) {
                $geofence->addPoint(new Coordinate($coord[1], $coord[0]));
            }

            if ($geofence->contains($target)) {
                return true;
            }
        } else {
            foreach ($coordList as $subCoordList) {
                if ($this->shapeContains($subCoordList, $target)) {
                    return true;
                }
            }
        }

        return false;
    }
}
