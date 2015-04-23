<?php namespace FinalProject\Commands\Test;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

use FinalProject\Support\Source;
use FinalProject\Support\Finder;
/**
 * Test various utilities in the Symfony DomCrawler & Articrawl Extension
 *
 * @package FinalProject\Commands
 */
class TextCommand extends Command {

    protected $sample;

    function __construct() {
        parent::__construct();
        $this->sample = Samples::exampleDotCom();
    }

    /**
     * Setup our Text method
     */
    protected function configure()
    {
        $this
            ->setName('test:text')
            ->setDescription('Run the text() method on the url provided (defaults to example.com)')
            ->setHelp("Get the inner text of the first <p> tag for example.com")
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'Enter a URL to inspect.',
                'http://www.example.com/'
            )
            ->addOption(
                'filter',
                's',
                InputOption::VALUE_OPTIONAL,
                'Provide a filter to narrow our nodes?',
                null
            )
            ->addOption(
                'first',
                'f',
                InputOption::VALUE_NONE,
                'Only show the first match.'
            )
            ->addOption(
                'last',
                'l',
                InputOption::VALUE_NONE,
                'Only show the last match.'
            )
            ->addOption(
                'clean',
                'c',
                InputOption::VALUE_NONE,
                'Clean the text output.'
            );
    }

    /**
     * Return the text() from DomCrawler for a url and tag
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $url = $input->getArgument('url');
        $filter = $input->getOption('filter');
        $clean = $input->getOption('clean');

        /**
         * Download the content in a SourceResult object and Create a new Crawler
         */
        $web = Source::curl($url, \Config::curl());
        $html = new Crawler($web->getSource());

        /**
         * Find a node to get the text for
         */
        $tags = is_null($filter) ? $html : $html->filter($filter);

        // Output the first match
        if ($input->getOption('first')) {
            $out = $tags->first()->text();
            $out = $clean ? regex_remove_extraneous_whitespace($out) : $out;
            print "First: $out\n";

        // Output the last match
        } else if($input->getOption('last')) {
            $out = $tags->last()->text();
            $out = $clean ? regex_remove_extraneous_whitespace($out) : $out;
            print "Last: $out\n";

        // Output the text for all matches and optionally remove whitespace with -c
        } else {
            $tags->each(function($n, $i) use($clean){
                if (regex_remove_whitespace($n->text()) == "") {
                    print "$i: empty!\n";
                } else {
                    $out = $n->text();
                    $out = $clean ? regex_remove_extraneous_whitespace($out) : $out;
                    print "$i: $out\n";
                }
            });
        }
    }

}