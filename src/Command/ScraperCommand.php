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
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ScraperCommand extends Command {
  /**
   * @var ParameterBagInterface
   */
  private ParameterBagInterface $parameters;

  /**
   * @var Logger
   */
  private $logger;

  /**
   * @var Client
   */
  private $client;

  public function __construct(ParameterBagInterface $parameters,
                              Logger $logger,
                              Client $client,
                              string $name = null) {
    parent::__construct($name);

    $this->parameters = $parameters;
    $this->logger = $logger;
    $this->client = $client;
  }

  protected function configure() {
    $this
        ->setDescription('Starts parsing')
        ->setHelp('This command launches the parser');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    try {
      $res = $this->client->get($this->parameters->get('allDepartmentsUrl'));
    } catch (RequestException $e) {
      $this->logger->error($e->getMessage());
      $this->logger->error($e->getTraceAsString());

      die();
    }

    $output->write($res->getBody()->getContents());

    return 0;
  }
}