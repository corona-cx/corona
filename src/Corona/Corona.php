<?php declare(strict_types=1);

namespace App\Corona;

use App\Entity\Area;
use App\Entity\Data;
use App\Entity\Shape;
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
        $shapeList = $this->managerRegistry->getRepository(Shape::class)->findAll();

        /** @var Shape $shape */
        foreach ($shapeList as $shape) {
            $coordList = json_decode($shape->getCoordList());
            $geofence = new Polygon();

            foreach ($coordList as $coord) {
                $geofence->addPoint(new Coordinate($coord[1], $coord[0]));
            }

            if ($geofence->contains($target)) {
                $area = $shape->getArea();
                return $this->managerRegistry->getRepository(Data::class)->findLatestForArea($area);
            }
        }

        return null;
    }
}
