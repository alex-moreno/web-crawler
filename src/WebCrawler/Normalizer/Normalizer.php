<?php
/**
 * User: alexmoreno
 * Date: 22/05/2015
 * Time: 18:54
 */

namespace WebCrawler;

/**
 * Class Normalizer
 *
 * @TODO.md: standarize for reuse
 */
class Normalizer {

  /**
   * Normalize arrays, so they can be merged with the content type easily.
   *
   * @TODO.md: this normalizer must be universal.
   *
   */
  public function normalizeArray($arrayNonNormalized) {
    $normalizedArray = array();

    foreach($arrayNonNormalized as $cruise) {
      $cheaperPrice = PHP_INT_MAX;

      foreach ($cruise['dates'] as $index_date=>$date) {

        echo PHP_EOL . 'Date: '. $date;
//        $normalizedArray[$index_date]['cheaperPrice'] = PHP_INT_MAX;
        $normalizedArray[$index_date]['date'] = $date;
        foreach ($cruise['prices'] as $prices) {
          echo PHP_EOL . 'prices: ' . $prices[$index_date];
          $normalizedArray[$index_date]['price'][] = $prices[$index_date];

          // Cheaper price found for that date?
          if ((is_numeric($prices[$index_date])) && ($cheaperPrice > $prices[$index_date])) {
            $normalizedArray[$index_date]['cheaperPrice'] = $prices[$index_date];
          }
        }
      }

      echo 'normalized::';

      print_r($normalizedArray);
      echo 'chepaer: ' . $cheaperPrice;
      $normalizedArray['cheaper'] = $cheaperPrice;
      $finalArray[] = $normalizedArray;
    }

  }

  /**
   * Structure will be an array of arrays like that:
   *
   * (
   * [0] => ---
   * [1] => 775
   * [2] => 694
   * [3] => 600
   * )
   *
   * where each group has as many elements as dates has the cruise (4 that case).
   * @todo: save the cheaper in a new field.
   * @TODO.md: this normalizer must be universal
   */
  public function normalizeMuchoViaje($cruiseListNonNormalized) {
    $finalPriceList = array();

    print_r($cruiseListNonNormalized);

    foreach($cruiseListNonNormalized as $cruiseList) {
      foreach($cruiseListNonNormalized['prices'] as $price) {
        // Remove non numeric (or ---) characters leaving one space ready for the explode.
        $pricesExploded = preg_replace("/[^0-9,\., -]/", " ", $price);

        // Remove . (dots).
        $pricesExploded = preg_replace("/\./", "", $pricesExploded);
        // If we have more than one space, leave just one.
        $pricesExploded = preg_replace('/ {1,}/' ,' ', $pricesExploded);

        // Explode in array, excluding ending/beginning blank spaces.
        $finalPriceList[] = explode(' ', trim($pricesExploded));

      }

      // Final data ready to return.
      $auxCruise['url'] = $cruiseList['url_to_offer'];
      $auxCruise['dates'] = $cruiseList['dates'];
      $auxCruise['prices'] = $finalPriceList;

//      // Final array.
//      $this->listOfCruisesPrices[] = $auxCruise;
    }
  }
} 