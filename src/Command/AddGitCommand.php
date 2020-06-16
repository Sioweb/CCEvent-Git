<?php

namespace Sioweb\CCEvent\Git\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
/**
 * My own command
 *
 * Demo command for learning
 */
class AddGitCommand extends ContainerAwareCommand
{
    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var array
     */
    private $rows = [];

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var string
     */
    private $webDir;

    /**
     * @var int
     */
    private $statusCode = 0;

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('ccevent:add:git')
            ->setDefinition([
                new InputArgument('repository', InputArgument::REQUIRED, 'The url to the git repository'),
                new InputArgument('package', InputArgument::REQUIRED, 'The package name'),
                new InputOption('target-dir', 'td', InputOption::VALUE_OPTIONAL, 'The composer target dir'),
                new InputOption('vendor-dir', 'vd', InputOption::VALUE_OPTIONAL, 'The composer vendor dir'),
            ])
            ->setDescription('Initialize the git repository, in the package for better development in vendor (Only recomended on localhost!).')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        echo "Loading git repositories.\n";
        $this->io = new SymfonyStyle($input, $output);
        $this->rootDir = $this->getContainer()->getParameter('kernel.project_dir');
        $this->gitUrl = rtrim($input->getArgument('repository'), '/');
        $this->packageName = rtrim($input->getArgument('package'), '/');
        exec("cd \"".$this->rootDir.'/vendor/'.$this->packageName."\" && rm -Rf .git && git init && git remote add origin ".$this->gitUrl." && git fetch --all && git reset --hard origin/master 2>&1", $output);
        // print_r($output);
        // die();

        // if (!empty($this->rows)) {
        //     $this->io->newLine();
        //     $this->io->table(['', 'GIT', 'Url / Error'], $this->rows);
        // }

        return $this->statusCode;
    }
}