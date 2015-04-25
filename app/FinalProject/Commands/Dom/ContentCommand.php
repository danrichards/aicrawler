<?php namespace FinalProject\Commands\Dom;

use FinalProject\Support\SourceNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use FinalProject\Support\Source;
use FinalProject\Support\Articrawler;
use FinalProject\Scraper;
/**
 * Test various utilities in the Symfony DomCrawler & Articrawl Extension
 *
 * @package FinalProject\Commands
 */
class ContentCommand extends Command {

    /**
     * Setup our Command
     */
    protected function configure()
    {
        $this->setName('dom:content')
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
            $web = Source::both($url, \Config::curl());
            $html = new Articrawler($web->getSource());
            $s = new Scraper($html);

            if ($s->content()->count()) {
                if ($dump) {
                    $this->outputMany($output, $s, $min, $filter);
                } else {
                    $this->outputSingle($output, $s);
                }
            } else {
                $output->writeln("Sorry, we couldn't find a headline.");
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
        $first = $s->content()->first();
        $output->writeln($first->nodeName() . ", Scoring ".number_format($first->getScoreTotal("content"), 1)." amongst " . $s->content()->count() . " considerations.");
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
        foreach ($s->content() as $c) {
            $score = $c->getScoreTotal("content");
            if ($score >= $min) {
                if ($filter === false || regex_set_contains_substr($c->text(), $filter))
                    $output->writeln($c->nodeName() . " Score (" . number_format($score, 1) . "): \tExtra: " . microdump($c->getExtra(), true) . "\t" . substr(regex_remove_extraneous_whitespace($c->text()), 0, 140));
            }
        }
    }

}