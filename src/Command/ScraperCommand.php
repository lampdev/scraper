<?php
/**
 * Main command
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScraperCommand extends Command {
  // the name of the command (the part after "bin/console")
  protected static $defaultName = 'app:run-scraper';

  protected function configure() {
    $this
        ->setDescription('Starts parsing')
        ->setHelp('This command launches the parser');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $output->write('Hello');

    return 0;
  }
}