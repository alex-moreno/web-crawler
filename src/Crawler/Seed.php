<?php
/**
 * Created by PhpStorm.
 * User: alexmoreno
 * Date: 22/05/2015
 * Time: 19:06
 */

namespace Crawler;

class Seed {

  protected $seed = NULL;

  protected $url;
  protected $language;

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
    $this->stages = $seed['stages'];
  }

  public function getURL() {
    return $this->url;
  }

  public function getStages() {
    return $this->stages;
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
