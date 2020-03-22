<?php
/**
 * Main command
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Monolog\Logger;

class ScraperCommand extends Command {
  /**
   * @var ParameterBagInterface
   */
  private ParameterBagInterface $parameters;

  /**
   * @var Logger
   */
  private $logger;

  public function __construct(ParameterBagInterface $parameters, Logger $logger, string $name = null) {
    parent::__construct($name);

    $this->parameters = $parameters;
    $this->logger = $logger;
  }

  protected function configure() {
    $this
        ->setDescription('Starts parsing')
        ->setHelp('This command launches the parser');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $output->write('Hello ' . $this->parameters->get('base_url'));

    return 0;
  }
}