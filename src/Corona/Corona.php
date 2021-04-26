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

        $body = [
            'query' => [
                'bool' => [
                    'must' => [
                        'match_all' => new \stdClass(),
                    ],
                    'filter' => [
                        'geo_shape' => [
                            'shape' => [
                                'shape' => [
                                    'type' => 'point',
                                    'coordinates' => [
                                        $target->getLng(),
                                        $target->getLat(),
                                    ]
                                ],
                                'relation' => 'contains',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $result = $client->get('http://localhost:9200/corona-shape/area_shape/_search', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($body),
            ]);
        } catch (ClientException $exception) {
            return null;
        }

        $resultObject = json_decode($result->getBody()->getContents());

        if (!$resultObject) {
            return null;
        }

        $hits = $resultObject->hits->hits;

        if (0 === count($hits)) {
            return null;
        }
        $shapeHit = array_pop($hits);

        $shapeId = $shapeHit->_id;

        /** @var Shape $shape */
        $shape = $this->managerRegistry->getRepository(Shape::class)->find($shapeId);

        if (!$shape) {
            return null;
        }

        $area = $shape->getArea();
        $data = $this->managerRegistry->getRepository(Data::class)->findLatestForArea($area);

        return $data;
    }
}
