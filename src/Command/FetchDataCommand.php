<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Area;
use App\Entity\Data;
use App\Source\GeoJsonSource;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use GeoJson\Feature\Feature;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FetchDataCommand extends Command
{
    protected GeoJsonSource $geoJsonSource;
    protected ManagerRegistry $managerRegistry;

    protected static $defaultName = 'corona:fetch-data';

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

        $areaList = $this->managerRegistry->getRepository(Area::class)->findAllIndexedByObjectId();

        $featureCollection = $this->geoJsonSource->getFeatureCollection();

        /** @var Feature $feature */
        foreach ($featureCollection as $feature) {
            $featureProperties = $feature->getProperties();
            $data = new Data();

            $carbonTimezone = new CarbonTimeZone('Europe/Berlin');
            $dateTimeString = trim(str_replace('Uhr', '', $featureProperties['last_update']));
            $carbonDateTime = Carbon::createFromTimeString($dateTimeString, $carbonTimezone);

            $area = $areaList[$featureProperties['OBJECTID']];

            $data
                ->setArea($area)
                ->setDateTime($carbonDateTime)
                ->setDeathRate($featureProperties['death_rate'])
                ->setCases($featureProperties['cases'])
                ->setDeaths($featureProperties['deaths'])
                ->setCasesPer100k($featureProperties['cases_per_100k'])
                ->setCasesPerPopulation($featureProperties['cases_per_population'])
                ->setCases7Per100k($featureProperties['cases7_per_100k'])
                ->setRecovered($featureProperties['recovered'])
                ->setCases7BlPer100K($featureProperties['cases7_bl_per_100k'])
                ->setCases7Bl($featureProperties['cases7_bl'])
                ->setDeath7Bl($featureProperties['death7_bl'])
                ->setCases7Lk($featureProperties['cases7_lk'])
                ->setDeath7Lk($featureProperties['death7_lk'])
            ;

            $this
                ->managerRegistry
                ->getManager()
                ->persist($data)
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
