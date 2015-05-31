<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\ClassLoader\Psr4ClassLoader;

$loader = new Psr4ClassLoader();
$loader->addPrefix('Symfony\\Component\\Yaml\\', __DIR__ . '/vendor/symfony/yaml/');

// Custom classes.
$loader->addPrefix('WebCrawler\\', __DIR__ . '/src/WebCrawler');
$loader->register();

use Symfony\Component\Yaml\Yaml;
use Crawler\Parser\SeedHandler;
use Crawler\Crawler;

// Config file.
// @TODO.md: move into a class.
$file = __DIR__ . '/config/crawler.config.yaml';
$yaml = new Yaml();

try {
  $parser = new \WebCrawler\Parser\SeedHandler($file);

  // Use Guzzle.
  $fetcher = new \WebCrawler\Fetcher\GuzzleFetcher();

  // Use Stubs mode to create and/or fetch some stubs:
//  $fetcher = new \WebCrawler\Stub\StubFetcher();

  // Create the crawler with the chosen config.
  $test = new \WebCrawler\WebCrawler($parser, $fetcher);

// And trigger the crawl.
  $test->bootCrawl();
} catch (Exception $ex) {
  echo 'Exception found' . $ex->getMessage();
}
