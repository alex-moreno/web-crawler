<?php
/**
 * Created by PhpStorm.
 * User: alexmoreno
 * Date: 06/10/15
 * Time: 13:09
 */

namespace WebCrawler\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use WebCrawler\Fetcher\FetcherInterface;
use WebCrawler\Fetcher\GuzzleFetcher;
use WebCrawler\Seeds\Seed;

// todo: rename urlCrawler.
class CustomCrawler {

  protected $newURLPattern;
  protected $newTargetPattern;

  /** @var  Crawler $newURLs */
  protected $newURLs;

  /** @var  Crawler $target */
  protected $target;

  /**
   * Crawl the url in search of the patterns.
   *
   * @param FetcherInterface $fetcher
   *   Fetcher to use when crawling urls.
   * @param string $url
   *   URL to crawl.
   * @param $newURLPattern
   *   Pattern for new url's.
   * @param $targetPattern
   *   Pattern to find the target.
   */
  public function __construct($url, $newURLPattern, $newTargetPattern, FetcherInterface $fetcher = NULL) {
    if (empty($fetcher)) {
      $this->fetcher = new GuzzleFetcher();
    }
    else {
      $this->fetcher = $fetcher;
    }

    $this->url = $url;
    $this->newURLPattern = $newURLPattern;
    $this->newTargetPattern = $newTargetPattern;

    $this->crawl();
  }

  /**
   * Crawl the url in search of the patterns.
   */
  public function crawl() {
    // @TODO: move to get methods.

    // @TODO: Add more than one pattern
    $this->newURLs = $this->fetcher->doFetch($this->url, $this->newURLPattern);

    // @TODO: Add more than one pattern
    $this->target = $this->fetcher->doFetch($this->url, $this->newTargetPattern);
  }

  /**
   * Return all new URL that the page can have based on the given $this->newURLPattern.
   *
   * @return array
   *   Array of string containing new urls found with potential targets.
   */
  public function getNewURLs() {
    // @TODO: cache this result.
    $elemsFound = NULL;

    /** @var \DOMElement $domElement */
    foreach ($this->newURLs as $domElement) {
      $elemsFound[] = $domElement->getAttribute('href');
    }

    if ($elemsFound) {
      return $elemsFound;
    }

    return FALSE;
  }

  /**
   * Return all targets if any found based on the given $this->newTargetPattern.
   *
   * @return array
   *   New targets found.
   */
  public function getTarget() {
    // @TODO: cache this result.
    $elemsFound = array();

    /** @var \DOMElement $domElement */
    foreach ($this->target as $domElement) {
      // Attribute for targets must be different and multiple.
      $elemsFound[] = $domElement->textContent;
    }

    return $elemsFound;
  }

}