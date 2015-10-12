<?php
/**
 * Created by PhpStorm.
 * User: alexmoreno
 * Date: 22/05/2015
 * Time: 19:06
 */

namespace WebCrawler\Seeds;

class Seed {

  protected $seed = NULL;
  protected $url;
  protected $language;
  protected $target;
  protected $crawlTarget;
  protected $newUrlsPattern;

  /** @var array List of stages with their crawling instructions */
  protected $stages = array();

  /**
   * Array with the list of seeds
   * @param array $seed
   *
   */
  public function __construct(Array $seed) {
    $this->seed = $seed;
    $this->url = $seed['url'];
    $this->language = $seed['language'];
    // Pattern to find a possible target.
    $this->targetPattern = $seed['targetPattern'];
    // Pattern to find new URL's.
    $this->newUrlsPattern = $seed['newUrlsPattern'];

    /* @deprecated */
    $this->stages = $seed['stages'];
  }

  /**
   * Get feed url.
   *
   * @return string
   *   String with the URL to crawl.
   */
  public function getURL() {
    return $this->url;
  }

  /**
   * @deprecated
   */
  public function getStages() {
    return $this->stages;
  }

  /**
   * Get which one will be the target.
   */
  public function getTargetPattern() {
    return $this->targetPattern;
  }

  /**
   * Get which patter we'll use to fetch new urls.
   *
   * @return string
   *   Pattern to find new urls.
   */
  public function getNewUrlsPattern() {
    return $this->newUrlsPattern;
  }

  /**
   * Get number of stages in this seed.
   *
   * @return int
   */
  public function getNumStages() {
    return count($this->stages);
  }
}
