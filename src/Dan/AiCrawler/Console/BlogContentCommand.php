<?php

namespace Dan\AiCrawler\Console;

use Illuminate\Console\Command;
use Dan\Core\Helpers\Dump;
use Dan\Core\Helpers\RegEx;
use Dan\AiCrawler\Scrapers\BlogScraper;
use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;

/**
 * Test Blog Scrapers
 *
 * @package AiCrawler\Commands
 */
class BlogContentCommand extends Command {

    protected $signature = "crawl:blog:content
        {url : A url to scrape.}
        {--top : Only dump the top consideration.}
        {--min=0 : Dump considerations that score higher than a minimum.}
        {--filter=false : Apply a filter to the dump.}";

    protected $description = "Scrape blog content from a web page.";

    /**
     * Run our command
     */
    protected function handle()
    {
        $url = $this->argument('url');
        $top = $this->option('top');
        $min = $this->option('min');
        $filter = $this->option('filter');

        /**
         * Download, Scrape the Crawler, Output
         */
        try {
            $blog = new BlogScraper($url);
            $content = $blog->setExtra("link", $url)->scrape()->choose()->getPayload("content");

            if ($content->count()) {
                if ($top) {
                    $this->outputFirst($content);
                } else {
                    $this->outputMany($content, $min, $filter);
                }
            } else {
                $this->info("Sorry, we couldn't find content.");
            }
        } catch (SourceNotFoundException $e) {
            $this->error("Unable to download the source with curl. ".$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $this->error("A crawler method was called with a bad argument. ".$e->getMessage());
        }
    }

    /**
     * @param $content
     */
    private function outputFirst($content)
    {
        $first = $content->first();
        $name = $first->nodeName();
        $score = number_format($first->getScoreTotal("content"), 1);
        $count = $content->count();
        $extra = $first->getExtra();

        $this->info("{$name}, Scoring {$score} amongst {$count} considerations.");
        $this->info("Extra: " . Dump::micro($extra, true));
        $this->info("Text: ");
        $this->info(substr(RegEx::removeExtraneousWhitespace($first->text()), 0, 500));
        $this->info("HTML: ");
        $this->info(substr(RegEx::removeExtraneousWhitespace($first->html()), 0, 1000));
    }

    /**
     * Output many results from the scraper
     *
     * @param $collection
     * @param $min
     * @param $filter
     */
    private function outputMany($collection, $min, $filter)
    {
        $collection->each(function ($node) use($min, $filter){
            $score = $node->getScoreTotal("content");
            if ($score >= $min) {
                if ($filter === false || RegEx::containsSubstr($node->text(), $filter)) {
                    $name = $node->nodeName();
                    $score = number_format($score, 1);
                    $extra = Dump::micro($node->getExtra(), true);
                    $first500 = substr(RegEx::removeExtraneousWhitespace($node->text()), 0, 500);
                    $this->info("{$name} Score ({$score}) \t Extra: {$extra} \t{$first500} \n");
                }
            }
        });
    }

}