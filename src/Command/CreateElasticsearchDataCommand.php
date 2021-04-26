<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Shape;
use App\Source\GeoJsonSource;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateElasticsearchDataCommand extends Command
{
    protected GeoJsonSource $geoJsonSource;
    protected ManagerRegistry $managerRegistry;

    protected static $defaultName = 'corona:create-elasticsearch-data';

    public function __construct(string $name = null, GeoJsonSource $geoJsonSource, ManagerRegistry $managerRegistry)
    {
        $this->geoJsonSource = $geoJsonSource;
        $this->managerRegistry = $managerRegistry;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Clear all results')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new Client();
        
        try {
            $client->delete('http://localhost:9200/corona-shape');

            $client->put('http://localhost:9200/corona-shape', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => '{
    "mappings": {
        "area_shape": {
            "properties": {
                "shape": {
                    "type": "geo_shape",
                    "strategy": "recursive"
                }
            }
        }
    }
}'
            ]);

            $shapeList = $this->managerRegistry->getRepository(Shape::class)->findAll();

            /** @var Shape $shape */
            foreach ($shapeList as $shape) {
                $body = [
                    'shape' => [
                        'type' => 'polygon',
                        'coordinates' => [
                            json_decode($shape->getCoordList()),
                        ]
                    ]
                ];


                $client->post('http://localhost:9200/corona-shape/area_shape', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($body),
                ]);
            }
        } catch (ClientException $exception) {
            dd($exception->getResponse()->getBody()->getContents());
        }


        return self::SUCCESS;
    }
}
