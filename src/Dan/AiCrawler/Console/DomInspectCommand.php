<?php namespace Dan\AiCrawler\Console\Dom;

use Dan\AiCrawler\Support\HtmlSourceDownloader;
use Illuminate\Console\Command;
use Dan\Core\Helpers\RegEx;
use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Source;

/**
 * Test various utilities in the Symfony DomCrawler & Articrawl Extension
 *
 * @package AiCrawler\Commands
 */
class InspectCommand extends Command {

    protected $config;

    /**
     * Setup our Commmand
     */
    protected function configure() {
        $this->config = new AiConfig();
        $this->setName('dom:inspect')
            ->setDescription('Get details DOM info on a url and filter.')
            ->setHelp("e.g. php crawl dom:inspect http://www.example.com/ --filter=p -l \t\t// Outputs details on last p tag")
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
     */
    protected function handle() {
        $url = $this->argument('url');
        $filter = $this->option('filter');

        if(!$filter)
            $this->info("You will not see any output unless you supply a CSS filter. (e.g. --filter=\"div > p\")");

        /**
         * Download the content in a SourceResult object and Create a new Crawler
         */
        $web = HtmlSourceDownloader::get($url);
        $html = new AiCrawler($web->getSource());

        /**
         * Find a node to get the text for
         */
        $matches = is_null($filter) ? $html : $html->filter($filter);

        // Output the first match
        if ($this->option('first')) {
            $n = $matches->first();
            $out = "\n".$n->nodeName()."\n";
            $out .= "Text: ".RegEx::removeExtraneousWhitespace($n->text())."\n";
            $out .= "HTML: ".$n->html()."\n";
            $this->info("$out\n");

        // Output the last match
        } else if($t = $this->option('last')) {
            $n = $matches->last();
            $out = "\n".$n->nodeName()."\n";
            $out .= "Text: ".RegEx::removeExtraneousWhitespace($n->text())."\n";
            $out .= "Children: ".$n->children()->count()."\n";
            $out .= "HTML: ".RegEx::removeExtraneousWhitespace($n->html(), true)."\n";
            $this->info("$out\n");

        // Output the text for all matches and optionally remove whitespace with -c
        } else {
            $matches->each(function($n, $i) use ($output) {
                if ($n->parents()->count() > 2) {
                    $out = "\n".$n->nodeName()."\n";
                    $text = RegEx::removeExtraneousWhitespace($n->text());
                    $out .= "Words(".$n->numWords()."), Characters(".$n->numCharacters().")\n";
                    $out .= "Text: ".$text."\n";
                    $out .= "Parents(".$n->parents()->count()."), Children(".$n->children()->count()."), Siblings(".$n->siblings()->count().")\n";
                    $out .= "HTML: ".$n->html()."\n";
                    $this->info("$out\n");
                }
            });
        }
    }

}