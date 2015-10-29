<?php namespace Dan\AiCrawler\Console\Blog;

use Illuminate\Console\Command;
use Dan\Core\Helpers\Dump;
use Dan\Core\Helpers\RegEx;
use Dan\AiCrawler\Scrapers\BlogScraper;
use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;

/**
 * Test various utilities in the Symfony DomCrawler & Articrawl Extension
 *
 * @package AiCrawler\Commands
 */
class BlogJsonCommand extends Command {



    /**
     * Setup our Command
     */
    protected function configure()
    {
        $this->setName('blog:json')
            ->setDescription('Convert our contexts into an json object (for an API) for consumption.')
            ->setHelp("e.g. http://www.example.com/")
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'Enter a URL to inspect.',
                'http://www.example.com/'
            );
    }

    /**
     * Run our command
     */
    protected function handle()
    {
        $url = $this->argument('url');
        $top = $this->option('top');

        /**
         * Download, Scrape the Crawler, Output
         */
        try {
            $blog = new BlogScraper($url);
            /**
             * Sort the context(headline, content, and image) Collections
             */
            $payload = $blog->setExtra("link", $url)->scrape()->choose()->getPayload();
            $json = $blog->response();

            if ($payload["content"]->count()) {
                $this->info(json_encode($json, JSON_PRETTY_PRINT));
            } else {
                $this->info("Sorry, we couldn't find content.");
            }
        } catch (SourceNotFoundException $e) {
            $this->info("Unable to download the source with curl. ".$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $this->info("A crawler method was called with a bad argument. ".$e->getMessage());
        }
    }

}