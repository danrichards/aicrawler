<?php namespace Dan\AiCrawler\Commands\Blog;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Source;
use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;
use Dan\AiCrawler\Scrapers\BlogScraper;

/**
 * Test various utilities in the Symfony DomCrawler & Articrawl Extension
 *
 * @package AiCrawler\Commands
 */
class DateCommand extends Command {

    /**
     * Setup our Command
     */
    protected function configure()
    {
        $this->setName('blog:date')
            ->setDescription('Search the DOM for an article\'s masthead image.')
            ->setHelp("e.g. http://www.example.com/")
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'Enter a URL to inspect.',
                'http://www.example.com/'
            )
            ->addOption(
                'dump',
                'd',
                InputOption::VALUE_NONE,
                'Dump all the considerations.'
            );
    }

    /**
     * Run our command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        $dump = $input->getOption('dump');
        /**
         * Download, Scrape the Crawler, Output
         */
        try {
            $web = Source::both($url, \Config::curl());
            $html = new AiCrawler($web->getSource());
            $s = new BlogScraper($html);

            if ($s->content()->count()) {
                // TODO: handle results from ImageHeuristic
                $output->writeln("TODO: handle results from ImageHeuristic");
            } else {
                $output->writeln("Sorry, we couldn't find an image.");
            }
        } catch (SourceNotFoundException $e) {
            $output->writeln("Unable to download the source with curl. ".$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $output->writeln("A crawler method was called with a bad argument. ".$e->getMessage());
        }
    }

}