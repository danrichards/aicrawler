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
 * Test various utilities in the Symfony DomCrawler & AiCrawler Extension
 *
 * @package AiCrawler\Commands
 */
class ImageCommand extends Command {

    protected $sample;

    /**
     * Setup our Command
     */
    protected function configure()
    {
        $this->setName('blog:image')
            ->setDescription('Search the DOM for an article\'s image.')
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
                'Also, dump all the considerations.'
            );
    }

    /**
     * Run our command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $url = $input->getArgument('url');
        $dump = $input->getOption('dump');

        /**
         * Download, Scrape the Crawler, Output
         */
        try {
            $web = Source::both($url, \Config::curl());
            $html = new AiCrawler($web->getSource());
            $blog = new BlogScraper($html);

            $payload = $blog->scrape()->choose();

            if ($payload["image"]->count())
                $this->output($output, $dump, $payload["image"]);
            else
                $output->writeln("Sorry, we couldn't find an appropriate image.");
        } catch (SourceNotFoundException $e) {
            $output->writeln("Unable to download the source with curl. ".$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $output->writeln("A crawler method was called with a bad argument. ".$e->getMessage());
        }
    }

    /**
     * Output our data
     *
     * @param OutputInterface $output
     * @param $dump
     * @param $s
     */
    private function output(OutputInterface $output, $dump, $headlines)
    {
        if ($dump) {
            foreach ($headlines as $h)
                $output->writeln($h->nodeName() . " Score (" . number_format($h->getScoreTotal("headline"), 1) . "): \t" . regex_remove_extraneous_whitespace($h->text()));
        } else {
            $first = $headlines->first();
            $output->writeln($first->nodeName() . " Scoring " . number_format($first->getScoreTotal("headline"), 1) . " amongst " . $headlines->count() . " considerations.");
            $output->writeln(regex_remove_extraneous_whitespace($first->text()));
        }
    }

}