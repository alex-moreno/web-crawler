<?php

namespace WebCrawler\tests\IntegrationTests;
use WebCrawler\FeedsHandler\FeedsHandler;

/**
 * Created by PhpStorm.
 * User: alexmoreno
 * Date: 07/10/15
 * Time: 13:56
 */
class FeedsHandlerTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider storeSeedsData
   */
  public function testStoreSeeds($file, $expected) {
    // We'll test different yaml files.
    $feedsHandler = new FeedsHandler($file);

    $numFeeds = $feedsHandler->getNumberOfFeeds();

    $this->assertEquals($expected, $numFeeds);
  }

  /**
   * Dataprovider.
   *
   * @return array
   */
  public function storeSeedsData() {
    // List of yaml files to test.
    return array(
      array(
        __DIR__ . '/YamlTest/crawler.list.yaml',
        1
      ),
      array(
        __DIR__ . '/YamlTest/crawler2.list.yaml',
        1
      ),
      array(
        __DIR__ . '/YamlTest/crawler-multiple.list.yaml',
        2
      ),
    );
  }

}