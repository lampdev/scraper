<?php


namespace App\Service;

use PhantomInstaller\PhantomBinary;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Process\Process;

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
  public function getLinks(string $selector) : array {
    $process = new Process(
        [
            $this->binary::getBin(),
            realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR . self::PHANTOMJS_SOURCE_SCRIPT,
            $this->baseUrl . $this->url
        ]
    );

    $process->mustRun();

    $this->crawler->add($process->getOutput());


    return $this->crawler->filter($selector)->extract(['href', '_text']);
  }
}