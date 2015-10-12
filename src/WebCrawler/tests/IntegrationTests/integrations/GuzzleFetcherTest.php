<?php

namespace WebCrawler\tests\IntegrationTests;
use WebCrawler\Fetcher\FetcherInterface;
use WebCrawler\Fetcher\GuzzleFetcher;

/**
 * Created by PhpStorm.
 * User: alexmoreno
 * Date: 07/10/15
 * Time: 13:56
 */
class GuzzleFetcherTest extends \PHPUnit_Framework_TestCase {

  /** @var  FetcherInterface $fetcher */
  protected $fetcher;

  /**
   * Setup method.
   */
  public function setUp() {
    $this->fetcher = new GuzzleFetcher();
  }
  /**
   * @dataProvider storeSeedsData
   */
  public function testGetNewURLs($feeds) {
      $this->fetcher->doFetch($feeds['url'], $feeds['newUrlsPattern']);

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
          'url'=> 'http://www.muchoviaje.com/cruceros/CRUCEROS.ASP',
          'language'=>'es',
          'targetPatterns'=>'.a',
          // Select all links inside div with id navieras.
          'newUrlsPattern'=> '//div[@id="navieras"]//a',
        ),
      ),
//
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