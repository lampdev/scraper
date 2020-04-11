<?php


namespace App\Service;

use PhantomInstaller\PhantomBinary;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Process\Process;
use \Closure;

class PhantomJS {
  /**
   * @var string
   */
  public const PHANTOMJS_SOURCE_SCRIPT = 'phantomjs/getsource.js';

  /**
   * @var PhantomBinary
   */
  private PhantomBinary $binary;

  /**
   * @var string
   */
  private string $url;

  /**
   * @var string
   */
  private string $baseUrl;

  /**
   * @var Crawler
   */
  private Crawler $crawler;

  /**
   * PhantomJS constructor.
   *
   * @param PhantomBinary $binary
   * @param Crawler $crawler
   * @param string $baseUrl
   */
  public function __construct(
      PhantomBinary $binary,
      Crawler $crawler,
      string $baseUrl) {
    $this->binary = $binary;
    $this->crawler = $crawler;
    $this->baseUrl = $baseUrl;
  }

  /**
   * @return string
   */
  public function getUrl(): string {
    return $this->url;
  }

  /**
   * @param string $url
   */
  public function setUrl(string $url): void {
    $this->url = $url;
  }

  /**
   * Returns array of hrefs => text for the given filter from the page
   *
   * @param string $selector
   *
   * @return array
   */
  public function getCategoryLinks(string $selector): array {
    $this->crawler->clear();
    $this->crawler->add($this->getPageSource());

    return array_map(
        Closure::fromCallable([$this, 'ensureRelativeLinks']),
        $this->crawler->filter($selector)->extract(
            [
                'href',
                '_text'
            ]
        )
    );
  }

  /**
   * Returns array of items for the given page
   *
   * @param string $selector for general item card
   * @param array $cardSelectors for properties of an item
   *
   * @return array
   */
  public function getItems(string $selector, array $cardSelectors): array {
    $this->crawler->clear();
    $this->crawler->add($this->getPageSource());

    return $this->crawler->filter($selector)->each(function(Crawler $node) use($cardSelectors) {
        return $this->getItemDetails($node, $cardSelectors);
      }
    );
  }

  /**
   * Returns an array for an item with all required params (title, price, ...)
   *
   * @param Crawler $node
   * @param array $cardSelectors
   *
   * @return array
   */
  private function getItemDetails(Crawler $node, array $cardSelectors): array {
    return [
        'img' => $node->filter('img')->extract(['src']),
        'title' => $node->filter($cardSelectors['title'])->extract(['_text']),
        'price' => $node->filter($cardSelectors['price'])->extract(['_text']),
        'soldBy' => $node->filter($cardSelectors['soldBy'])->extract(['_text']),
        'rating' => $node->filter($cardSelectors['rating'])->extract(['_text']),
    ];
  }

  /**
   * Returns processed page source
   *
   * @return string
   */
  private function getPageSource(): string {
    $process = new Process(
        [
            $this->binary::getBin(),
            realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR . self::PHANTOMJS_SOURCE_SCRIPT,
            $this->baseUrl . $this->url
        ]
    );

    $process->mustRun();

    return $process->getOutput();
  }

  /**
   * Closure to convert all links to absolute
   *
   * @param array $element
   *
   * @return array
   */
  private function ensureRelativeLinks(array $element): array {
    list($url, $name) = $element;
    if (strpos($url, 'http') === 0) {
      $url = parse_url($url)['path'];
    }

    return [
        $url,
        $name
    ];
  }
}