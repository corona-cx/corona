<?php declare(strict_types=1);

namespace App\Command;

use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use Location\Coordinate;
use Location\Polygon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CoronaFetchCommand extends Command
{
    protected static $defaultName = 'corona:fetch';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $content = file_get_contents('https://services7.arcgis.com/mOBPykOjAyBO2ZKk/arcgis/rest/services/RKI_Landkreisdaten/FeatureServer/0/query?where=1%3D1&objectIds=&time=&geometry=&geometryType=esriGeometryPolygon&inSR=&spatialRel=esriSpatialRelIntersects&resultType=none&distance=0.0&units=esriSRUnit_Meter&returnGeodetic=false&outFields=*&returnGeometry=true&returnCentroid=false&featureEncoding=esriDefault&multipatchOption=none&maxAllowableOffset=&geometryPrecision=&outSR=&datumTransformation=&applyVCSProjection=false&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnExtentOnly=false&returnQueryGeometry=true&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&returnZ=false&returnM=false&returnExceededLimitFeatures=true&quantizationParameters=&sqlFormat=none&f=pgeojson&token=');

        $json = json_decode($content);

        /** @var FeatureCollection $featureCollection */
        $featureCollection = \GeoJson\GeoJson::jsonUnserialize($json);

        $iterator = $featureCollection->getIterator();

        /** @var Feature $feature */
        while ($feature = $iterator->current()) {
            $coordList = $feature->getGeometry()->getCoordinates()[0];
            $geofence = new Polygon();

            foreach ($coordList as $coord) {
                $geofence->addPoint(new Coordinate($coord[1], $coord[0]));
            }

            $kiel = new Coordinate(54.321496, 10.138726);

            $match = $geofence->contains($kiel);

            dump($match);

            if ($match) {
                dump($feature);
                break;
            }

            $iterator->next();
        }

        return Command::SUCCESS;
    }
}
