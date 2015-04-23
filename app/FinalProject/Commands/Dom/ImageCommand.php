<?php namespace FinalProject\Commands\Dom;

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
class ImageCommand extends Command {

    protected $sample;

    /**
     * Setup our Text method
     */
    protected function configure()
    {
        $this->setName('dom:image')
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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        $dump = $input->getOption('dump');

        /**
         * Download the image in a SourceResult object and Create a new Crawler
         */
        $web = Source::curl($url, \Config::curl());
        $html = new Articrawler($web->getSource());
        $s = new Scraper($html);

        $image = $s->image();

        if ($image->count()) {
            if ($dump) {
                foreach ($image as $c)
                    $output->writeln($c->nodeName() . " Score (" . $c->getScore("headline") . "): " . $c->html());
            } else {
                $first = $image->first();
                $output->writeln($first->nodeName() . " Score (" . $first()->getScore("headline") . "): " . $first->html());
            }
        } else {
            $output->writeln("Sorry, we couldn't find a headline.");
        }
    }
}