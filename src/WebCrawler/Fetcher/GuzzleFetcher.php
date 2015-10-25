<?php
/**
 * @file
 * GuzzleFetcher.php
 *
 * User: alexmoreno
 * Date: 30/05/2015
 * Time: 10:49
 */

namespace WebCrawler\Fetcher;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Net_URL2;

class GuzzleFetcher implements FetcherInterface {

  /** @var \GuzzleHttp\Client $client Client to fetch the urls */
  protected $client;

  protected $crawler;

  /**
   * Default constructor.
   *
   * @param \Goutte\Client $client
   */
  public function __construct($client = NULL) {
    if (!empty($client)) {
      $this->client = $client;
    }
    else {
      // We'll use Gouette by default.
      $this->client = new Client();
    }

    }

  /**
   * Fetch the $attrs on the $url based on the given $pattern.
   *
   * @param $url
   *   URL to Fetch.
   * @param $pattern
   *   Patter to fetch.
   * @param array $attrs
   *   Attributes to fetch (we can fetch several in each url).
   *
   * @return Crawler
   *   Return the array of results found.
   * @throws \RuntimeException
   */
  public function doFetch($url, $pattern) {

    /** @var Crawler $client */
    $crawler = $this->client->request('GET', $url);

    // Find (filter) the contents in the url based on the pattern.
    /** @var Crawler $foundContents */
    return $crawler->filterXPath($pattern);
  }

  /**
   * Return the Absolute url given a relative and an absolute one.
   *
   * @param $relativeURL
   *  Url from which to fetch the absolute.
   * @param $absoluteURL
   *  Base url.
   *
   * @return string
   */
  public function URLResolver($absoluteURL, $relativeURL) {
    $base = new Net_URL2($absoluteURL);

    return $base->resolve($relativeURL)->getNormalizedURL();
  }

}
