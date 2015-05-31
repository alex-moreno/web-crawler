<?php

namespace WebCrawler\Parser;


// @todo: don't like this name. FeedsHandler?
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
   * @return array
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
