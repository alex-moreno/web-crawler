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
  private $guzzleFetcher;
  private $crawler;

  protected function setUp() {
    // Mocks for external / network services.
    $this->client = $this->getMockBuilder('\Goutte\Client')
      ->disableOriginalConstructor()
      ->getMock();

    // Mocks for external / network services.
    $this->crawler = $this->getMockBuilder('\Crawler')
      ->disableOriginalConstructor()
      ->getMock();

    $this->guzzleFetcher = new GuzzleFetcher($this->client, $this->crawler);
  }

  /**
   * @dataProvider DoFetchData
   */
  public function testDoFetch() {
    // Create an stub.
    $this->client->expects($this->any())
      ->method('request')
      ->will($this->returnValue(1));
    // Create an stub.
    $this->crawler->expects($this->any())
      ->method('filter')
      ->will($this->returnValue(1));


//    $this->guzzleFetcher->doFetch($url, $pattern, $attrs);
    $this->assertTrue(TRUE);
  }

  /**
   * Data for listAware->add.
   *
   * @return array
   *   Array of testable data.
   */
  public function DoFetchData() {
    return array(
      array(
        'url',
        'pattern',
        'attr'
      ),
    );
  }
}
