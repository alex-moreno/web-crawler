<?php

namespace WebCrawler\FeedsHandler;


// @todo: don't like this name. FeedsHandler?
use WebCrawler\Seeds\Seed;

interface FeedsHandlerInterface {

  /**
   * Store seeds from the given array in the list of seeds object.
   *
   * @param array $seeds
   *   List of seeds.
   */
  public function storeSeeds(array $seeds);

  /**
   * Get seeds used to crawl.
   *
   * @return Seed
   */
  public function getSeeds();

  /**
   * Get number of feeds.
   *
   * @return int
   *   Number of feeds.
   */
  public function getNumberOfFeeds();
}
