<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Area;
use App\Entity\Shape;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConvertShapeCommand extends Command
{
    protected static $defaultName = 'corona:convert-shape';
    protected static $defaultDescription = 'Add a short description for your command';
    protected ManagerRegistry $managerRegistry;

    public function __construct(string $name = null, ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $areaList = $this->managerRegistry->getRepository(Area::class)->findAll();

        /** @var Area $area */
        foreach ($areaList as $area) {
            $coordList = json_decode($area->getShape());
            $this->createShape($area, $coordList);
        }

        $this->managerRegistry->getManager()->flush();

        return Command::SUCCESS;
    }

    protected function createShape(Area $area, array $coordList): void
    {
        $firstElement = $coordList[0];

        if (2 === count($firstElement) && is_float($firstElement[0]) && is_float($firstElement[1])) {
            $shape = new Shape();
            $shape
                ->setArea($area)
                ->setCoordList(json_encode($coordList))
            ;

            $this->managerRegistry->getManager()->persist($shape);
        } else {
            foreach ($coordList as $subCoordList) {
               $this->createShape($area, $subCoordList);
            }
        }
    }
}
