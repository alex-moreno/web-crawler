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
   * @dataProvider storeSeedsNewUrlsData
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
   * @dataProvider storeSeedsTargetsData
   */
  public function testGetTarget($feeds) {
    // We'll test different yaml files.
    $customCrawler = new CustomCrawler($feeds['url'], $feeds['newUrlsPattern'], $feeds['targetPatterns']);

    $results = $customCrawler->getTarget();

    echo 'results::';
    print_r($results);

    // @todo: better test than simply not empty.
    if (is_array($results)) {
      // It has at least one element.
      $this->assertArrayHasKey(0, $results);
      // And the element is not empty
      $this->assertFalse($results[0] == '');
    }
  }


  /**
   * Dataprovider.
   * @TODO: use local saved htmls, instead of URLs.
   *
   * @return array
   */
  public function storeSeedsTargetsData() {
    // List of yaml files to test.
    return array(
      // find new target.
      array(
        // Test finding new urls
        array(
          'seed'=>'',
          'url'=> 'http://reservascruceros.muchoviaje.com/muchocruceros/Ofertas/ofertas.aspx?iditinerario=76293&idbarco=308&barco=Sovereign%20&itinerario=Espa%C3%B1a%20,%20Reino%20unido%20,%20En%20el%20mar%20,%20Islas%20canarias%20&naviera=Pullmantur%20Cruceros%20Web',
          'language'=>'es',
          'targetPatterns'=> '//span[@class="precioDetalles"]',
          // Select all links inside div with id navieras.
          'newUrlsPattern'=> NULL,
        ),
      ),

      // find new target.
      array(
        // Test finding new urls
        array(
          'seed'=>'',
          'url'=> 'http://reservascruceros.muchoviaje.com/muchocruceros/Ofertas/ofertas.aspx?iditinerario=68333&idbarco=208&barco=Costa%20Pac%C3%ADfica&itinerario=Barcelona%20,%20Cruising%20,%20M%C3%A1laga%20,%20Casablanca%20(marruecos)%20,%20Cruising%20,%20Funchal%20(madeira)%20,%20Sta.%20cruz%20de%20tenerife%20(espa%C3%B1a)%20&naviera=Costa%20Cruceros',
          'language'=>'es',
          'targetPatterns' => '//span[@class="precioDetalles"]',
          // Select all links inside div with id navieras.
          'newUrlsPattern' => NULL,
        ),
      ),


      // find new target.
      array(
        // Test finding new urls
        array(
          'seed'=>'',
          'url'=> 'http://reservascruceros.muchoviaje.com/muchocruceros/Ofertas/ofertas.aspx?iditinerario=68333&idbarco=208&barco=Costa%20Pac%C3%ADfica&itinerario=Barcelona%20,%20Cruising%20,%20M%C3%A1laga%20,%20Casablanca%20(marruecos)%20,%20Cruising%20,%20Funchal%20(madeira)%20,%20Sta.%20cruz%20de%20tenerife%20(espa%C3%B1a)%20&naviera=Costa%20Cruceros',
          'language'=>'es',
          // @TODO: Add more than one pattern
          'targetPatterns' => '//h4[@class="detallesbarco"]',
          // Select all links inside div with id navieras.
          'newUrlsPattern' => NULL,
        ),
      ),

    );
  }



  /**
   * Dataprovider.
   * @TODO: use local saved htmls, instead of URLs.
   *
   * @return array
   */
  public function storeSeedsNewUrlsData() {
    // List of yaml files to test.
    return array(

      // find new url's.
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


    );
  }

}