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
   * Trigger the crawl.
   */
  public function bootCrawl() {

    foreach ($this->seedHandler->getSeeds() as $seed) {
      $urlSeed = $seed->getURL();
      $stages = $seed->getStages();

      $this->doCrawl($urlSeed, $stages);

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
   */
  public function doCrawl($url, $stages) {
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
