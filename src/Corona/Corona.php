<?php declare(strict_types=1);

namespace App\Corona;

use App\Entity\Area;
use App\Entity\Data;
use App\Entity\Shape;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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
        $client = new Client();

        try {
            $result = $client->get('http://localhost:9200/corona-shape/area_shape/_search', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => '{
    "query":{
        "bool": {
            "must": {
                "match_all": {}
            },
            "filter": {
                "geo_shape": {
                    "shape": {
                        "shape": {
                            "type": "point",
                            "coordinates" : ['.$target->getLng().', '.$target->getLat().']
                        },
                        "relation": "contains"
                    }
                }
            }
        }
    }
}',
            ]);
        } catch (ClientException $exception) {
            dd($exception->getResponse()->getBody()->getContents());
        }

        $resultObject = json_decode($result->getBody()->getContents());

        $hits = $resultObject->hits->hits;
        $shapeHit = array_pop($hits);

        $shapeId = $shapeHit->_id;

        /** @var Shape $shape */
        $shape = $this->managerRegistry->getRepository(Shape::class)->find($shapeId);
        $area = $shape->getArea();

        $data = $this->managerRegistry->getRepository(Data::class)->findLatestForArea($area);

        return $data;

        return null;
    }
}
