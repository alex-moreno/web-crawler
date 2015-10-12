<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\ClassLoader\Psr4ClassLoader;

$loader = new Psr4ClassLoader();
$loader->addPrefix('Symfony\\Component\\Yaml\\', __DIR__ . '/vendor/symfony/yaml/');

// Custom classes.
$loader->addPrefix('WebCrawler\\', __DIR__ . '/src/WebCrawler');
$loader->register();

use Symfony\Component\Yaml\Yaml;

// Config file.
// @TODO.md: move into a class.
//$file = __DIR__ . '/config/crawler.config.yaml';
$file = __DIR__ . '/config/crawler.list.yaml';
$yaml = new Yaml();

try {
  $seed = new \WebCrawler\FeedsHandler\FeedsHandler($file);

  // Use Guzzle.
  $fetcher = new \WebCrawler\Fetcher\GuzzleFetcher();

  // Use Stubs mode to create and/or fetch some stubs:
//  $fetcher = new \WebCrawler\Stub\StubFetcher();

  // Create the crawler with the chosen config.
  $crawler = new \WebCrawler\WebCrawler($seed, $fetcher);

// And trigger the crawl.
  $crawler->bootCrawl();
} catch (Exception $ex) {
  echo 'Exception found: ' . $ex->getMessage();
}
