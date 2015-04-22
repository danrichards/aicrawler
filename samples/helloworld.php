<?php include __DIR__.'/../vendor/autoload.php';

/**
 * Hello World Example
 *
 * @source https://github.com/symfony/DomCrawler
 */
use Symfony\Component\DomCrawler\Crawler;

$crawler = new Crawler();
$crawler->addContent('<html><body><p>Hello World!</p></body></html>');

echo $crawler->filterXPath('descendant-or-self::body/p')->text();
echo "\n\nNow using CssSelector Component\n\n";
echo $crawler->filter('body > p')->text() . "\n";