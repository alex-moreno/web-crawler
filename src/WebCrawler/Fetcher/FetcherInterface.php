<?php

/**
 * @file
 * FetcherInterface.php
 */

namespace WebCrawler\Fetcher;

interface FetcherInterface {

  /**
   * Fetch the $url using the $pattern.
   *
   * @param $url
   *   URL to fecth from.
   * @param $pattern
   *   Pattern to use in regex format.
   *
   * @return string
   *
   */
  public function doFetch($url, $pattern);
}
