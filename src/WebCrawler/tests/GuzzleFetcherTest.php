<?php

use WebCrawler\Fetcher\GuzzleFetcher;
/**
 * Created by PhpStorm.
 * User: alexmoreno
 * Date: 17/09/15
 * Time: 15:20
 */
class GuzzleFetcherTest extends PHPUnit_Framework_TestCase {

  private $client;

  protected function setUp() {
    $this->client = new GuzzleFetcher();
//    $fetcher = new \WebCrawler\Fetcher\GuzzleFetcher();

  }

  public function testDoFetch() {
    $this->assertTrue(FALSE);
  }

}
