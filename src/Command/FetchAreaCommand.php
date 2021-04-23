<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Area;
use App\Source\GeoJsonSource;
use Doctrine\Persistence\ManagerRegistry;
use GeoJson\Feature\Feature;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FetchAreaCommand extends Command
{
    protected GeoJsonSource $geoJsonSource;
    protected ManagerRegistry $managerRegistry;

    protected static $defaultName = 'corona:fetch-area';

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
        $io = new SymfonyStyle($input, $output);

        $featureCollection = $this->geoJsonSource->getFeatureCollection();

        /** @var Feature $feature */
        foreach ($featureCollection as $feature) {
            $featureProperties = $feature->getProperties();
            $serializedAreaShape = json_encode($feature->getGeometry()->getCoordinates());

            $area = new Area();

            $area
                ->setCounty($featureProperties['county'])
                ->setFederalState($featureProperties['BL'])
                ->setName($featureProperties['GEN'])
                ->setNuts($featureProperties['NUTS'])
                ->setPopulation($featureProperties['EWZ'])
                ->setPopulationFederalState($featureProperties['EWZ_BL'])
                ->setType($featureProperties['BEZ'])
                ->setShape($serializedAreaShape)
            ;

            $this
                ->managerRegistry
                ->getManager()
                ->persist($area)
            ;
        }

        $this
            ->managerRegistry
            ->getManager()
            ->flush()
        ;

        return self::SUCCESS;
    }
}
