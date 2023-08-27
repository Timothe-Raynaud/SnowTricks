<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;


class CleanTemporaryDirectoryCommand extends Command
{
    protected static $defaultName = 'app:clean-temporary-directory';

    private Filesystem $filesystem;
    private ParameterBagInterface $parameterBag;

    public function __construct(Filesystem $filesystem, ParameterBagInterface $parameterBag)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;
    }

    // Clean all file that was update as more as 15 minutes.
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = date("Y-m-d H:i:s");
        $time = strtotime($date);
        $time -= (15 * 60);
        $date = date("Y-m-d H:i:s", $time);

        $finder = new Finder();
        $finder->date('<=' . $date);
        $tmpDirectory = $this->parameterBag->get('images_temporary');
        $finder->files()->in($tmpDirectory);

        foreach ($finder as $file){
            $this->filesystem->remove($file->getRealPath());
        }

        return Command::SUCCESS;
    }
}
