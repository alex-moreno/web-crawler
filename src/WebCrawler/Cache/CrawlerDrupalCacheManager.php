<?php
/**
 * @file
 * CrawlerCacheManager.php
 */

namespace Crawler\Cache;

class CrawlerDrupalCacheManager implements CrawlerCacheManagerInterface {
  protected $cacheLifeTime;
  protected $cacheBin;

  /**
   * Constructor.
   */
  public function __construct($cache_life_time, $cache_bin) {
    $this->cacheLifeTime = $cache_life_time;
    $this->cacheBin = $cache_bin;
  }

  /**
   * Set the cache in the given Drupal cache bin.
   *
   * @param string $cache_key
   *   Cache key as String
   * @param array $data
   *   Data as array.
   */
  public function setCache($cache_key, $data) {
    cache_set($cache_key, $data, $this->cacheBin, (time() + (int) $this->cacheLifeTime));
  }

  /**
   * Get cache.
   *
   * @param string $cache_key
   *   Return cached object/array
   *
   * @return object/array
   *   Cached object if any.
   */
  public function getCache($cache_key) {
    $cache_data = cache_get($cache_key, $this->cacheBin);

    if (isset($cache_data->data)) {
      return $cache_data->data;
    }

    return NULL;
  }

  /**
   * Flush cache.
   *
   * @param string $cache_key
   *   Cache key or * to flush all cache.
   */
  public function flushCache($cache_key = '*') {
    if ($cache_key == '*') {
      cache_clear_all($cache_key, $this->cacheBin, TRUE);
    }
    else {
      cache_clear_all($cache_key, $this->cacheBin);
    }
  }
}
