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
   *   Attributes to fetch (we can fetch several in each url).
   *
   * @return array|string
   *   Return the array of results found.
   * @throws \RuntimeException
   */
  public function doFetch($url, $pattern, $attrs = array()) {
    $nodes = array();

    // @TODO: find if there is a cache hit.

    // Do the Fecth itself.
    $crawler = $this->client->request('GET', $url);

    echo 'got url';

    // Find (filter) the contents in the url based on the pattern.
    $filtered_contents = $crawler->filter($pattern);

    // @todo: if attr != last stage.
    if (iterator_count($filtered_contents) > 1) {

      // iterate over filter results
      foreach ($filtered_contents as $content) {
        // create crawler instance for result
        $crawler = new Crawler($content);

        // @TODO: we may have multiple attributes to fetch per each url.
        foreach ($attrs as $attr) {

          // Fetch the attribute $attr.
          try {
            // Let's fetch the content based on the attribute we've passed.
            $newAttrContent = $crawler->attr($attr);
            $newAttrContent = $this->URLResolver($url, $newAttrContent);

            // Attribute which we'll be returning (URL, content itself, ...).
            $nodes[][$attr] = $newAttrContent;
          }
          catch (\Exception $ex) {
            // We don't mind if some url's are empty, we'll just continue and.
            // let the previous level to decide.
            echo PHP_EOL . 'Failed in ' . $newAttrContent;
          }
        }
      }
    }
    // @todo: if attr == last stage.
    else {

      // Final value which we'll store in DDBB.
//      echo PHP_EOL . ' Final results: ' . $filtered_contents->text();
//      echo PHP_EOL . 'url : ' . $url;
      // TODO: We need to store several values, like price, texts, ...
      echo PHP_EOL . 'attr: ' . $attrs[0];
      $nodes['result'][]['price'] = $filtered_contents->text();

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

    return $base->resolve($relativeURL)->getNormalizedURL();
  }

}
