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

    // @TODO: cache.

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
      /** @var Seed $seed */
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

      echo PHP_EOL . 'stage: ';
      print_r($stage);

      if (empty($results)) {
        // We are in the first iteration.
        $results[$indexCurrentStage] = $this->fetcher->doFetch($url, $stage['selector'], array($stage['fetch']));

        // We store previous stage.
        $indexPreviousStage = $indexCurrentStage;
      }
      else {
        // In subsequent iterations, we'll crawl the next urls found in previous stages.
        // We'll iterate through results in previous stage feeding next stages.

        // Once results is empty, we'll fill it again with new results found in
        // the current stage to be fed in the next one.
        $lastStage = count($stages) == $indexCurrentStage;
        $results = $this->fetchResultsCurrentStage($results[$indexPreviousStage], $indexCurrentStage, $stage['selector'], $stage['fetch'], $lastStage);


        // We store previous stage results.
        $indexPreviousStage = $indexCurrentStage;
      }

      // @todo: remove debug
      continue;
    }

    print_r($results);

  }

  /**
   * Fetch results for the URL's in $results in the current stage.
   *
   * @param $results
   *   Results in current iteration.
   * @param $indexCurrentStage
   *   Current stage number (stated in the list.yaml.
   * @param $selector
   *   Selector to use to fetch data.
   * @param $op
   *   Operation to execute.
   * @param $lastStage
   *   Are in the last stage.
   *
   * @return array
   *   Results for the current stage.
   */
  public function fetchResultsCurrentStage($results, $indexCurrentStage, $selector, $op, $lastStage = FALSE) {
    $resultsCurrentStage = array();

    // We initzialise again the current results with new ones.
    foreach ($results as $result) {
      try {
        $resultsCurrentStage[$indexCurrentStage] = $this->fetcher->doFetch($result['href'], $selector, array($op));
        // If array merge?

        // If last stage.
        if($indexCurrentStage == 'stage3') {
          $resultsCurrentStage['finalresults'][] = $resultsCurrentStage[$indexCurrentStage];
        }

      } catch (\Exception $ex) {
        // We don't mind if it does not find results, carry on with more URL's.
        echo PHP_EOL . 'No results found. Continuing in next iteration. Stage: ' . $indexCurrentStage . ' Selector: ' . $selector;
        echo PHP_EOL . 'url: ' . $result['href'];

      }

      // @todo: remove debug
      continue;
    }

    return $resultsCurrentStage;
  }

}
