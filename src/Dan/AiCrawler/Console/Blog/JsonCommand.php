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
class JsonCommand extends Command {

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
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');

        /**
         * Download, Scrape the Crawler, Output
         */
        try {
            $blog = new BlogScraper($url);
            /**
             * Sort the context(headline, content, and image) Collections
             */
            $payload = $blog->setExtra("link", $url)->scrape()->choose();
//            var_dump($payload['headline']->first());
//            print "\n\n\n";
            $json = $blog->render();

            if ($payload["content"]->count()) {
                $output->writeln(json_encode($json, JSON_PRETTY_PRINT));
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