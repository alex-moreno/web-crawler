<?php
/**
 * @file WebCrawler
 */

namespace WebCrawler;

use WebCrawler\Crawler\CustomCrawler;
use WebCrawler\Fetcher\FetcherInterface;
use WebCrawler\FeedsHandler\FeedsHandlerInterface;
use WebCrawler\Seeds\Seed;
use WebCrawler\Storage\CrawlerStorage;
use Symfony\Component\DomCrawler\Crawler;

class WebCrawler {

  /** @var FeedsHandlerInterface $seedHandler */
  protected $seedHandler;

  protected $storate;
  /** @var \WebCrawler\Fetcher\FetcherInterface Fetcher engine */
  protected $fetcher;

  protected $urlFeed;

  protected $newUrlsPattern;
  protected $targetPattern;

  /**
   * @var array|Target List of targets found.
   */
  protected $targets;


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
      // Drupal? BBDD? Doctrine?
      $this->storate = new CrawlerStorage();
    }

  }

  /**
   * Trigger the crawl.
   */
  public function bootCrawl() {
    $this->seeds = $this->seedHandler->getSeeds();

    // For each seed (ie: Logi, Muchoviaje, ...)
    /** @var Seed $seed */
    foreach ($this->seeds as $seed) {
      /** @var Seed $seed */
      // First url to start crawling.
      $$this->urlFeed[] = $seed->getURL();

      // Target and newurls pattern for the current seed.
      $this->newUrlsPattern = $seed->getNewUrlsPattern();
      $this->targetPattern = $seed->getTargetPattern();

      $this->doCrawl();
    }
  }

  /**
   * Crawl based on the url's left in $this->urlFeed.
   */
  public function doCrawl() {

    // We'll be looping while urlfeed is not empty.
    while (!empty($this->urlFeed)) {
      // Remove the current url from the queue.
      $currentURL = array_pop($this->urlFeed);

      $crawler = new CustomCrawler($this->fetcher, $currentURL, $this->newUrlsPattern, $this->targetPattern);
      if($crawler->hasNewURLs()) {
        // If we find new urls, add them to the queue.
        array_merge($this->urlFeed, $crawler->getNewURLs());
      }

      if($crawler->hasTarget()) {
        // If we find new targets, add them
        $this->targets[] = $crawler->getTarget();
        // @todo: do something with the targets, trigger an event, store in db,
      }
    }
  }

//    foreach ($stages as $indexCurrentStage => $stage) {
//
//      echo PHP_EOL . 'stage: ';
//      print_r($stage);
//
//      if (empty($results)) {
//        // We are in the first iteration.
//        $results[$indexCurrentStage] = $this->fetcher->doFetch($url, $stage['selector'], array($stage['fetch']));
//
//        // We store previous stage.
//        $indexPreviousStage = $indexCurrentStage;
//      }
//      else {
//        // In subsequent iterations, we'll crawl the next urls found in previous stages.
//        // We'll iterate through results in previous stage feeding next stages.
//
//        // Once results is empty, we'll fill it again with new results found in
//        // the current stage to be fed in the next one.
//        $lastStage = count($stages) == $indexCurrentStage;
//        $results = $this->fetchResultsCurrentStage($results[$indexPreviousStage], $indexCurrentStage, $stage['selector'], $stage['fetch'], $lastStage);
//
//
//        // We store previous stage results.
//        $indexPreviousStage = $indexCurrentStage;
//      }
//
//      // @todo: remove debug
//      continue;
//    }
//
//    print_r($results);
//
//  }

}
