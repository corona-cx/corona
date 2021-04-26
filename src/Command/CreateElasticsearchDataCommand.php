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
use Symfony\Component\Console\Style\SymfonyStyle;

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
            ->setDescription('Create index and populate with shapes.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $client = new Client();

        try {
            $client->delete('http://localhost:9200/corona-shape');
        } catch (ClientException | ServerException $exception) {
            $io->error('Could not delete index as it does not exist.');
        }

        $body = [
            'mappings' => [
                'area_shape' => [
                    'properties' => [
                        'shape' => [
                            'type' => 'geo_shape',
                            'strategy' => 'recursive',
                        ]
                    ]
                ]
            ]
        ];

        try {
            $client->put('http://localhost:9200/corona-shape', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($body),
            ]);
        } catch (ServerException | ClientException $exception) {
            $io->error('Could not create index.');
        }

        $shapeList = $this->managerRegistry->getRepository(Shape::class)->findAll();
        $io->progressStart(count($shapeList));

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

            try {
                $client->post('http://localhost:9200/corona-shape/area_shape/' . $shape->getId(), [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($body),
                ]);
            } catch (ClientException $exception) {
                $io->error(sprintf('Could not place shape #%d.', $shape->getId()));
            }

            $io->progressAdvance();
        }

        $io->progressFinish();

        $io->success(sprintf('Created index and placed %d shapes', count($shapeList)));

        return self::SUCCESS;
    }
}
