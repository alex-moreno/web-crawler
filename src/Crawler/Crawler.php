<?php
/**
 * @file Crawler
 */

namespace Crawler;

use Crawler\Fetcher\FetcherInterface;
use Crawler\Parser\FeedsHandlerInterface;
use Crawler\Storage\CrawlerStorage;

class Crawler {

  /** @var FeedsHandlerInterface $seedHandler */
  protected $seedHandler;

  protected $storate;
  /** @var \Crawler\Fetcher\FetcherInterface Fetcher engine */
  protected $fetcher;

  /**
   * Default constructor
   *
   * @param FeedsHandlerInterface $seed
   *   Class with all the seeds to search.
   * @param FetcherInterface $fetcher
   *   Fetcher to use for crawling
   * @param CrawlerStorage $storage
   *   Storage class.
   */
  public function __construct(FeedsHandlerInterface $seed, FetcherInterface $fetcher, CrawlerStorage $storage = NULL) {

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
  public function doCrawl() {

    foreach ($this->seedHandler->getSeeds() as $seed) {
      $urlSeed = $seed->getURL();
      $stages = $seed->getStages();

      $this->crawlStages($urlSeed, $stages);

    }
  }

  public function crawlStages($url, $stages) {
    $results = array();
    $indexPreviousStage = 0;

    foreach ($stages as $indexCurrentStage => $stage) {

      if (empty($results)) {
        // We are in the first iteration.
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

  public function fetchResultsCurrentStage($results, $indexCurrentStage, $selector, $fetch) {
    // @todo: Move into a function to help readability: $results = fetchResultsCurrentStage();.
    // We initzialise again the current results with new ones.
    foreach ($results as $result) {
      try {
        $resultsCurrentStage[$indexCurrentStage] = $this->fetcher->doFetch($result['href'], $selector, array($fetch));
      } catch (\Exception $ex) {
        // We don't mind if it does not find results.
        echo 'no results found. Continuing in next iteration.';
      }
    }


  }

}
