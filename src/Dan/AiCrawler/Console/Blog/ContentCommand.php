<?php namespace Dan\AiCrawler\Console\Blog;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


use Dan\AiCrawler\Scrapers\BlogScraper;
use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;

/**
 * Test various utilities in the Symfony DomCrawler & Articrawl Extension
 *
 * @package AiCrawler\Commands
 */
class ContentCommand extends Command {

    /**
     * Setup our Command
     */
    protected function configure()
    {
        $this->setName('blog:content')
            ->setDescription('Search the DOM for an article\'s content.')
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
            )
            ->addOption(
                'min',
                'm',
                InputOption::VALUE_OPTIONAL,
                'Only dump considerations that score higher than a minimum.',
                0
            )
            ->addOption(
                'filter',
                'f',
                InputOption::VALUE_OPTIONAL,
                'Apply a filter to the dump.',
                false
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
        $min = $input->getOption('min');
        $dump = $input->getOption('dump') || $min > 0;
        $filter = $input->getOption('filter');

        /**
         * Download, Scrape the Crawler, Output
         */
        try {
            $blog = new BlogScraper($url);

            $payload = $blog->setExtra("link", $url)->scrape()->choose();

            if ($payload["content"]->count()) {
                if ($dump)
                    $this->outputMany($output, $payload["content"], $min, $filter);
                else
                    $this->outputSingle($output, $payload["content"]);
            } else {
                $output->writeln("Sorry, we couldn't find content.");
            }
        } catch (SourceNotFoundException $e) {
            $output->writeln("Unable to download the source with curl. ".$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $output->writeln("A crawler method was called with a bad argument. ".$e->getMessage());
        }
    }

    /**
     * @param OutputInterface $output
     * @param $s
     */
    private function outputSingle(OutputInterface $output, $s)
    {
        $first = $s->first();
        $output->writeln($first->nodeName() . ", Scoring ".number_format($first->getScoreTotal("content"), 1)." amongst " . $s->count() . " considerations.");
        $output->writeln("Extra: " . microdump($first->getExtra(), true));
        $output->writeln("Text: ");
        $output->writeln(substr(regex_remove_extraneous_whitespace($first->text()), 0, 500));
        $output->writeln("HTML: ");
        $output->writeln(substr(regex_remove_extraneous_whitespace($first->html()), 0, 1000));
    }

    /**
     * @param OutputInterface $output
     * @param $s
     * @param $min
     * @param $filter
     */
    private function outputMany(OutputInterface $output, $s, $min, $filter)
    {
        $s->each(function ($node) use($output, $min, $filter){
            $score = $node->getScoreTotal("content");
            if ($score >= $min) {
                if ($filter === false || regex_set_contains_substr($node->text(), $filter))
                    $output->writeln($node->nodeName() .
                        " Score (" . number_format($score, 1) . "): \t
                          Extra: " . microdump($node->getExtra(), true) . "\t" .
                        substr(regex_remove_extraneous_whitespace($node->text()), 0, 500));
            }
        });
    }

}