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

    $this->phantomJS->setUrl($this->parameters->get('allCategories')['url']);
    $categories = $this->phantomJS->getCategoryLinks($this->parameters->get('allCategories')['linksSelector']);

    foreach ($categories as $category) {
      list($categoryUrl, $categoryName) = $category;

      $page = 1;
      $allItems = $uniqueness = [];

      do {
        $output->writeln('Category name: ' . $categoryName . '; Category URL: ' . $categoryUrl . '; page: ' . $page);

        $this->phantomJS->setUrl($categoryUrl . '?page=' . $page);
        $items = $this->phantomJS->getItems(
            $this->parameters->get('items')['linksSelector'],
            $this->parameters->get('items')['card']
        );

        $key = md5(json_encode($items));
        if (in_array($key, $uniqueness)) {
          break;
        }
        $uniqueness[] = $key;

        $allItems = array_merge($items,$allItems);

        $output->writeln('Found ' . count($items) . ' products');
        $page++;

      } while(count($items));

      var_dump($allItems);die();
    }

    return 0;
  }


}