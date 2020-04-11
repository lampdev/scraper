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
use App\Service\PhantomJS;

class ScraperCommand extends Command {
  /**
   * @var ParameterBagInterface
   */
  private ParameterBagInterface $parameters;

  /**
   * @var Logger
   */
  private Logger $logger;

  /**
   * @var PhantomJS
   */
  private PhantomJS $phantomJS;

  public function __construct(ParameterBagInterface $parameters,
                              Logger $logger,
                              PhantomJS $phantomJS,
                              string $name = null) {
    parent::__construct($name);

    $this->parameters = $parameters;
    $this->logger = $logger;
    $this->phantomJS = $phantomJS;
  }

  protected function configure() {
    $this
        ->setDescription('Starts parsing')
        ->setHelp('This command launches the parser');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $this->phantomJS->setUrl($this->parameters->get('allDepartmentsUrl'));
    $departments = $this->phantomJS->getLinks('div.alldeps-DepartmentLinks-columnList a.alldeps-DepartmentLinks-categoryList-categoryLink');

    return 0;
  }
}