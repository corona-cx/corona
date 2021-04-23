<?php

namespace App\Command;

use App\Source\GeoJsonSource;
use GeoJson\Feature\Feature;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FetchAreaCommand extends Command
{
    protected GeoJsonSource $geoJsonSource;
    protected static $defaultName = 'corona:fetch-area';

    public function __construct(string $name = null, GeoJsonSource $geoJsonSource)
    {
        $this->geoJsonSource = $geoJsonSource;

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
            dump($feature->getProperties());
        }
        return self::SUCCESS;
    }
}
