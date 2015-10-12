<?php

namespace WebCrawler\tests\IntegrationTests;
use WebCrawler\Crawler\CustomCrawler;
use WebCrawler\Fetcher\GuzzleFetcher;

/**
 * Created by PhpStorm.
 * User: alexmoreno
 * Date: 07/10/15
 * Time: 13:56
 */
class CustomCrawlerTest extends \PHPUnit_Framework_TestCase {

  protected $fetcher;

  /**
   * TODO: do these tests.
   */
  public function setUp() {
    $this->fetcher = new GuzzleFetcher();

  }

  /**
   * @dataProvider storeSeedsData
   */
  public function testGetNewURLs($feeds) {
    // We'll test different yaml files.
    $customCrawler = new CustomCrawler($feeds['url'], $feeds['newUrlsPattern'], $feeds['targetPatterns']);
    $customCrawler->getNewURLs();

    $customCrawler->getTarget();

    // @todo: better test than simply not empty.
    $this->assertNotEmpty($customCrawler);
  }

  /**
   * @dataProvider storeSeedsData
   */
  public function testGetTarget($feeds) {
    // We'll test different yaml files.
    $customCrawler = new CustomCrawler($feeds['url'], $feeds['newUrlsPattern'], $feeds['targetPatterns']);

    $customCrawler->getTarget();

//    $this->assertEquals($feeds['url'], $feedsHandler->getNewURLs());
  }


  /**
   * Dataprovider.
   * @TODO: use local saved htmls, instead of URLs.
   *
   * @return array
   */
  public function storeSeedsData() {
    // List of yaml files to test.
    return array(
      // find new url's.
      array(
        // Test finding new urls
        array(
          'seed'=>'',
          'url'=> 'http://www.muchoviaje.com/cruceros/CRUCEROS.ASP',
          'language'=>'es',
          'targetPatterns'=>'.a',
          // Select all links inside div with id navieras.
          'newUrlsPattern'=> '//div[@id="navieras"]//a',
        ),
      ),


      // @todo: Test finding targets
//      array(
//        array(
//          'seed'=>'',
//          'url'=> 'http://test',
//          'language'=>'en',
//          'targetPatterns'=>'.a',
//          'newUrlsPattern'=>'.div a',
//        ),
//      ),
//
//
//      array(
//        array(
//          'seed'=>'',
//          'url'=> 'http://test3',
//          'language'=>'en',
//          'targetPatterns'=>'.a',
//          'newUrlsPattern'=>'.div a',
//        ),
//      ),
//

    );
  }


}