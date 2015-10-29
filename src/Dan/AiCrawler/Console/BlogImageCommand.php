<?php

namespace Dan\AiCrawler\Console\Blog;

use Illuminate\Console\Command;
use Dan\Core\Helpers\RegEx;
use Dan\AiCrawler\Scrapers\BlogScraper;
use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;

/**
 * Test various utilities in the Symfony DomCrawler & AiCrawler Extension
 *
 * @package AiCrawler\Commands
 */
class BlogImageCommand extends Command {

    protected $signature = "crawl:blog:image
        {url : A url to scrape.}
        {--top : Only dump the top consideration.}";

    /**
     * Run our command
     */
    protected function handle() {
        $url = $this->argument('url');
        $top = $this->option('top');

        /**
         * Download, Scrape the Crawler, Output
         */
        try {
            $blog = new BlogScraper($url);
            $headlines = $blog->setExtra("link", $url)->scrape()->choose()->getPayload("headline");

            if ($headlines->count()) {
                if ($top) {
                    $first = $headlines->count();
                    $name = $first->nodeName();
                    $score = number_format($first->getScoreTotal("image"), 1);
                    $count = $headlines->count();
                    $this->info("{$name} Scoring {$score} amongst {$count} considerations.");
                    $this->info(RegEx::removeExtraneousWhitespace($first->text()));
                } else {
                    foreach ($headlines as $h) {
                        $name = $h->nodeName();
                        $score = number_format($h->getScoreTotal("image"), 1);
                        $content = RegEx::removeExtraneousWhitespace($h->text());
                        $this->info("{$name} Score ({$score}): \t{$content}\n";
                    }
                }
            } else {
                $this->info("Sorry, we couldn't find a headline.");
            }
        } catch (SourceNotFoundException $e) {
            $this->info("Unable to download the source with curl. ".$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $this->info("A crawler method was called with a bad argument. ".$e->getMessage());
        }
    }

}