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
  protected $targetPattern;

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
  public function __construct($url, $newURLPattern, $targetPattern, FetcherInterface $fetcher = NULL) {
    if (empty($fetcher)) {
      $this->fetcher = new GuzzleFetcher();
    }
    else {
      $this->fetcher = $fetcher;
    }

    $this->url = $url;
    $this->newURLPattern = $newURLPattern;
    $this->targetPattern = $targetPattern;

    $this->crawl();
  }

  /**
   * Crawl the url in search of the patterns.
   */
  public function crawl() {
    // @TODO: move to get methods.
    $this->newURLs = $this->fetcher->doFetch($this->url, $this->newURLPattern);
    $this->target = $this->fetcher->doFetch($this->url, $this->targetPattern);
  }

  /**
   * Return all new URL that the
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
   * Return target if any found.
   *
   * @return array|Target
   */
  public function getTarget() {
    // @TODO: cache this result.
    $elemsFound = NULL;

    echo 'testing targets';
    /** @var \DOMElement $domElement */
    foreach ($this->target as $domElement) {
      // Attribute for targets must be different and multiple.
      $elemsFound[] = $domElement->getAttribute('href');
    }

    if ($elemsFound) {
      // TODO: Create class Target?
      return $this->target;
    }

    return FALSE;
  }

}