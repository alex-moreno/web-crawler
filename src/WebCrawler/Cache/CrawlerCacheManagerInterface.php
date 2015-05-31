<?php
/**
 * @file
 * CrawlerCacheManagerInterface
 */

namespace Crawler\Cache;

interface CrawlerCacheManagerInterface {
  /**
   * Set the cache in the given Drupal cache bin.
   *
   * @param string $cache_key
   *   Cache key as String
   * @param array $data
   *   Data as array.
   */
  public function setCache($cache_key, $data);

  /**
   * Get cache.
   *
   * @param string $cache_key
   *   Return cached object/array
   *
   * @return object/array
   *   Cached object if any.
   */
  public function getCache($cache_key);
}
