<?php

namespace App\Command;

use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClearCacheCommand extends Command
{
    const KEY_PREFIX = 'corona_result';
    const NAMESPACE = 'criticalmass_corona';
    const TTL = 60 * 60;

    protected AbstractAdapter $adapter;
    protected static $defaultName = 'corona:clear-cache';

    public function __construct(string $name = null)
    {
        $this->adapter = new FilesystemAdapter(self::NAMESPACE, self::TTL);

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

        $pruneResult = $this->adapter->prune();

        if ($pruneResult) {
            $io->success('Cleared result cache.');

            return Command::SUCCESS;
        } else {
            $io->warning('Result cache clearing failed.');

            return Command::FAILURE;
        }
    }
}
