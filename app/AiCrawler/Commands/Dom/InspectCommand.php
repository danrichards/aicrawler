<?php namespace AiCrawler\Commands\Dom;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use AiCrawler\Support\Source;
use AiCrawler\Support\AiCrawler;

/**
 * Test various utilities in the Symfony DomCrawler & Articrawl Extension
 *
 * @package AiCrawler\Commands
 */
class InspectCommand extends Command {

    protected $sample;

    /**
     * Setup our Text method
     */
    protected function configure()
    {
        $this->setName('dom:inspect')
            ->setDescription('Get details DOM info on a url and filter.')
            ->setHelp("e.g. http://www.example.com/ --filter=p -l (outputs details on last p tag)")
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
                'What tag would you like to show the text for?',
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
            );
    }

    /**
     * Run the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $url = $input->getArgument('url');
        $filter = $input->getOption('filter');

        /**
         * Download the content in a SourceResult object and Create a new Crawler
         */
        $web = Source::curl($url, \Config::curl());
        $html = new AiCrawler($web->getSource());

        /**
         * Find a node to get the text for
         */
        $matches = is_null($filter) ? $html : $html->filter($filter);

        // Output the first match
        if ($input->getOption('first')) {
            $n = $matches->first();
            $out = "\n".$n->nodeName()."\n";
            $out .= "Text: ".regex_remove_extraneous_whitespace($n->text())."\n";
            $out .= "HTML: ".$n->html()."\n";
            print "$out\n";

        // Output the last match
        } else if($t = $input->getOption('last')) {
            $n = $matches->last();
            $out = "\n".$n->nodeName()."\n";
            $out .= "Text: ".regex_remove_extraneous_whitespace($n->text())."\n";
            $out .= "Children: ".$n->children()->count()."\n";
            $out .= "HTML: ".regex_remove_html($n->html(), true)."\n";
            print "$out\n";

        // Output the text for all matches and optionally remove whitespace with -c
        } else {
            $matches->each(function($n, $i){
                if ($n->parents()->count() > 2) {
                    $out = "\n".$n->nodeName()."\n";
                    $text = regex_remove_extraneous_whitespace($n->text());
                    $out .= "Words(".str_word_count($text)."), Characters(".count($text).")\n";
                    $out .= "Text: ".$text."\n";
                    $out .= "Parents(".$n->parents()->count()."), Children(".$n->children()->count()."), Siblings(".$n->siblings()->count().")\n";
                    $out .= "HTML: ".$n->html()."\n";
                    print "$out\n";
                }
            });
        }
    }

}