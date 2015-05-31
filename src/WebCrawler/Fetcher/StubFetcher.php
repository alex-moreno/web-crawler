<?php
/**
 * @file
 * This class stands for CrawlerStubService
 */

namespace WebCrawler\Stub;

use WebCrawler\Fetcher\FetcherInterface;

class StubFetcher implements FetcherInterface {

  /**
   * {@inherit}
   */
  public function doFetch($url, $pattern) {
    $this->stubResults($url);
  }

  /**
   * Create some stubs with the results coming from the url.
   * @param $url
   *   URL to create stubs from.
   */
  public function stubResults($url) {
    // Get the tracking Request.
    $contents = $this->client->request('GET', $url);

    if (isset($response_object)) {
      $file_path = isset($path) ? "$path/{$url}.stub" : __DIR__ . "/Fetcher/Stubs/{$url}.stub";
      file_put_contents($file_path, serialize($contents));
    }

  }

  /**
   * Fetch stubs from the given $url.
   *
   * @param $url
   *   URL to fetch stubs from.
   */
  public function fetchStub($url) {
    // Module path.
    $stub_path = __DIR__  . "/Stubs";

    if (isset($contents)) {
      if (file_exists("$stub_path/{$url}.stub")) {
        $this->webserviceCallResult = unserialize(file_get_contents("$stub_path/{$url}.stub"));
      }
    }
  }


}
