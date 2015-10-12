<?php

namespace WebCrawler\tests\IntegrationTests;
use WebCrawler\FeedsHandler\FeedsHandler;
use WebCrawler\Seeds\Seed;

/**
 * Created by PhpStorm.
 * User: alexmoreno
 * Date: 07/10/15
 * Time: 13:56
 */
class SeedTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider storeSeedsData
   */
  public function testGetURL($feeds) {
    // We'll test different yaml files.
    $feedsHandler = new Seed($feeds);

    $this->assertEquals($feeds['url'], $feedsHandler->getURL());
  }

  /**
   * @dataProvider storeSeedsData
   */
  public function testGetTargetPattern($feeds) {
    // We'll test different yaml files.
    $feedsHandler = new Seed($feeds);

    $this->assertEquals($feeds['targetPattern'], $feedsHandler->getTargetPattern());
  }

  /**
   * @dataProvider storeSeedsData
   */
  public function testNewUrlsPattern($feeds) {
    // We'll test different yaml files.
    $feedsHandler = new Seed($feeds);

    $this->assertEquals($feeds['newUrlsPattern'], $feedsHandler->getNewUrlsPattern());
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
        array(
          'seed'=>'',
          'url'=> 'http://test',
          'language'=>'es',
          'targetPatterns'=>'.a',
          'newUrlsPattern'=>'.div a',
        ),
      ),

      array(
        array(
          'seed'=>'',
          'url'=> 'http://test',
          'language'=>'en',
          'targetPatterns'=>'.a',
          'newUrlsPattern'=>'.div a',
        ),
      ),


      array(
        array(
          'seed'=>'',
          'url'=> 'http://test3',
          'language'=>'en',
          'targetPatterns'=>'.a',
          'newUrlsPattern'=>'.div a',
        ),
      ),


    );
  }

}