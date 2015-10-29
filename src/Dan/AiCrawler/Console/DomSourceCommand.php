<?php

namespace Dan\AiCrawler\Console\Dom;


use Dan\AiCrawler\Support\HtmlSourceDownloader;
use Illuminate\Console\Command;
use Dan\AiCrawler\Support\Source;
use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;

/**
 * Run BFS on URL and provide plenty of options to the user for output.
 *
 * @package AiCrawler\Commands
 */
class SourceCommand extends Command {

    protected $config;

    /**
     * Setup our Commmand
     */
    protected function configure() {
        $this->config = new AiConfig();

        $this
            ->setName('dom:source')
            ->setHelp("e.g. php crawl dom:source http://www.example.com/")
            ->setDescription('Just output the html source so we can check what the scraper is getting.')
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'Enter a URL to download the source with CURL or file_get_contents.',
                'http://www.example.com/'
            );
    }

    /**
     * Output some nodes BFS style.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function handle() {
        /**
         * What to search?
         */
        $url = $this->argument('url');
        if ($url == 'http://www.example.com/')
            $this->info("A URL argument was not provided, http://www.example.com will be used.");

        /**
         * Setup a crawler and output
         */
        try {
            $html = HtmlSourceDownloader::get($url, $this->config->get("curl"));
            $this->info($html->getSource());
        } catch (SourceNotFoundException $e) {
            $this->info("Unable to download the source with curl. ".$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $this->info("A crawler method was called with a bad argument. ".$e->getMessage());
        }
    }
}