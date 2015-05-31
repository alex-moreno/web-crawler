<?php

namespace Crawler\Parser;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Crawler\Seed;

// @todo: don't like this name. FeedsHandler?
class SeedHandler implements FeedsHandlerInterface {

  protected $seeds;

  /**
   * Default constructor.
   */
  public function __construct($file = '/path/to/file.yml') {

    try {
      $yaml = new Parser($file);
      $this->storeSeeds($yaml->parse(file_get_contents($file)));

    }
    catch (ParseException $e) {
      printf("Unable to parse the YAML string: %s", $e->getMessage());
    }

  }

  /**
   * Store seeds from the given array in the list of seeds object.
   *
   * @param array $seeds
   *   List of seeds.
   */
  public function storeSeeds(array $seeds) {

    // Store seeds in an array of objects.
    foreach ($seeds as $seed) {
      $this->seeds[] = new Seed($seed);
    }
  }

  /**
   * Get seeds used to crawl.
   *
   * @return array
   */
  public function getSeeds() {

    return $this->seeds;
  }

  /**
   * Get number of feeds.
   *
   * @return int
   *   Number of feeds.
   */
  public function getNumberOfFeeds() {
    return count($this->seeds);
  }
}
