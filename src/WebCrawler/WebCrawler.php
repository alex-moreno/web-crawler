<?php
/**
 * @file WebCrawler
 */

namespace WebCrawler;

use WebCrawler\Fetcher\FetcherInterface;
use WebCrawler\Parser\FeedsHandlerInterface;
use WebCrawler\Storage\CrawlerStorage;
//use Symfony\Component\DomCrawler\WebCrawler;

class WebCrawler {

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

//            echo PHP_EOL . 'fetched: ' . $newAttr;
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
   * Trigger the crawl.
   */
  public function bootCrawl() {

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
