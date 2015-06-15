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
   *   Attributes to fetch.
   *
   * @return array|string
   *   Return the array of results found.
   * @throws \RuntimeException
   */
  public function doFetch($url, $pattern, $attrs = array()) {
    $nodes = array();
    // Do the Fecth itself.
    $crawler = $this->client->request('GET', $url);


    $filtered_contents = $crawler->filter($pattern);
    if (iterator_count($filtered_contents) > 1) {

      // iterate over filter results
      foreach ($filtered_contents as $content) {
        // create crawler instance for result
        $crawler = new Crawler($content);

        // @TODO: attrs are currently list of url's. Need to standarize
        foreach ($attrs as $attr) {
          // Fetch the attribute $attr.
          try {

            // @TODO.md: Change this and accept fetchLinks or attributes/anything else.
            $newAttr = $crawler->attr($attr);
            if ($attr == 'href') {
              // We'll normalize the url.
              $newAttr = $this->URLResolver($url, $newAttr);
            }
            // Attribute which we'll be returning (URL, content itself, ...).
            $nodes[][$attr] = $newAttr;
          }
          catch (\Exception $ex) {
            // We don't mind if some url's are empty, we'll just continue and.
            // let the previous level to decide.
          }
        }
      }
    }
    else {
      throw new \RuntimeException('Got empty result processing the dataset!');
    }

    return $nodes;
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

    return $base->resolve($relativeURL)->getNormalizedURL();;
  }

}
