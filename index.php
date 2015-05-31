<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\ClassLoader\Psr4ClassLoader;

$loader = new Psr4ClassLoader();
$loader->addPrefix('Symfony\\Component\\Yaml\\', __DIR__ . '/vendor/symfony/yaml/');

// Custom classes.
$loader->addPrefix('Crawler\\', __DIR__ . '/src/Crawler');
$loader->register();

use Symfony\Component\Yaml\Yaml;
use Crawler\Parser\SeedHandler;
use Crawler\Crawler;

// Config file.
// @TODO: move into a class.
$file = __DIR__ . '/config/crawler.config.yaml';
$yaml = new Yaml();

try {
  $parser = new SeedHandler($file);
  $fetcher = new \Crawler\Fetcher\GuzzleFetcher();

  // Create the crawler with the chosen config.
  $test = new Crawler($parser, $fetcher);

// And trigger the crawl.
  $test->doCrawl();
} catch (Exception $ex) {
  echo 'Exception found' . $ex->getMessage();
}
