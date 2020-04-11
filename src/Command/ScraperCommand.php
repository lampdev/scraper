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
use Doctrine\DBAL\Connection;

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

  /**
   * @var Connection
   */
  private $connection;

  public function __construct(ParameterBagInterface $parameters,
                              Logger $logger,
                              PhantomJS $phantomJS,
                              Connection $connection,
                              string $name = null) {
    parent::__construct($name);

    $this->parameters = $parameters;
    $this->logger = $logger;
    $this->phantomJS = $phantomJS;
    $this->connection = $connection;
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

      foreach ($allItems as $item) {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->insert('products')
            ->values(
                [
                    'title'  => ':title',
                    'price'  => ':price',
                    'soldBy' => ':soldBy',
                    'rating' => ':rating'
                ]
            )
            ->setParameter(':title', $item['title'][0])
            ->setParameter(':price', $item['price'][0])
            ->setParameter(':soldBy', $item['soldBy'][0])
            ->setParameter(':rating', $item['rating'][0])
        ;

        $queryBuilder->execute();
      }
    }

    return 0;
  }


}