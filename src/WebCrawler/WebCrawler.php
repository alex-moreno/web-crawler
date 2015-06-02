<?php
/**
 * @file WebCrawler
 */

namespace WebCrawler;

use WebCrawler\Fetcher\FetcherInterface;
use WebCrawler\Parser\FeedsHandlerInterface;
use WebCrawler\Storage\CrawlerStorage;
use Symfony\Component\DomCrawler\Crawler;

class WebCrawler {

  /** @var FeedsHandlerInterface $seedHandler */
  protected $seedHandler;

  protected $storate;
  /** @var \WebCrawler\Fetcher\FetcherInterface Fetcher engine */
  protected $fetcher;

  /**
   * Default constructor
   *
   * @param FeedsHandlerInterface $seed
   *   Class with all the seeds to search.
   * @param FetcherInterface $fetcher
   *   Fetcher to use for crawling
   */
  public function __construct(FeedsHandlerInterface $seed, FetcherInterface $fetcher) {

    $this->seedHandler = $seed;
    $this->fetcher = $fetcher;

    if (!empty($storage)) {
      $this->storate = $storage;
    }
    else {
      $this->storate = new CrawlerStorage();
    }

  }


  /**
   * Fetch the $attrs in the $url based on the $pattern.
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
  private function doCrawl($url, $pattern, $attrs = array()) {
    $nodes = array();
    $crawler = $this->client->request('GET', $url);

    $filter = $crawler->filter($pattern);
    if (iterator_count($filter) > 1) {

      // iterate over filter results
      foreach ($filter as $content) {
        // create crawler instance for result
        $crawler = new Crawler($content);

        foreach ($attrs as $attr) {
          // Fetch the attribute $attr.
          try {

            // @TODO.md: Change this and accept fetchLinks or attributes/anything else.
            $newAttr = $crawler->attr($attr);
            if ($attr == 'href') {
              // We'll normalize the url.
              $newAttr = $this->URLResolver($url, $newAttr);
            }

            $nodes[][$attr] = $newAttr;
          } catch (\Exception $ex) {
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
   * Trigger the crawl.
   */
  public function bootCrawl() {

    foreach ($this->seedHandler->getSeeds() as $seed) {
      $urlSeed = $seed->getURL();
      $stages = $seed->getStages();

      $this->crawlStages($urlSeed, $stages);

    }
  }

  /**
   * Crawl based on the number of stages that the list.yaml states.
   *
   * @param $url
   *   URL to fetch data from.
   * @param $stages
   *   List of stages to crawl, with the url to fetch and the selector and
   *   operation (fetch, @todo add more).
   *
   */
  public function crawlStages($url, $stages) {
    $results = array();
    $indexPreviousStage = 0;

    foreach ($stages as $indexCurrentStage => $stage) {

      if (empty($results)) {
        // We are in the first iteration.
        // @todo: this does too much.
        $results[$indexCurrentStage] = $this->fetcher->doFetch($url, $stage['selector'], array($stage['fetch']));

        // We store previous stage.
        $indexPreviousStage = $indexCurrentStage;
      }
      else {
        // In subsequent iterations, we'll crawl the next urls found in previous stages.
        // We'll iterate through results in previous stage.

        // Once results is empty, we'll fill it again with new results found in
        // the current stage to be fed in the next one.
        $results = $this->fetchResultsCurrentStage($results[$indexPreviousStage], $indexCurrentStage, $stage['selector'], $stage['fetch']);

        // We store previous stage.
        $indexPreviousStage = $indexCurrentStage;
      }

    }
  }

  /**
   * Fetch results for the URL's in the current stage.
   *
   * @param $results
   *   Results in current iteration.
   * @param $indexCurrentStage
   *   Current stage number (stated in the list.yaml.
   * @param $selector
   *   Selector to use to fetch data.
   * @param $op
   *   operation to execute (
   */
  public function fetchResultsCurrentStage($results, $indexCurrentStage, $selector, $op) {
    // We initzialise again the current results with new ones.
    foreach ($results as $result) {
      try {
        $resultsCurrentStage[$indexCurrentStage] = $this->fetcher->doFetch($result['href'], $selector, array($op));
      } catch (\Exception $ex) {
        // We don't mind if it does not find results, carry on with more URL's.
        echo 'no results found. Continuing in next iteration.';
      }
    }

    return $resultsCurrentStage;
  }

}
