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
 * Test various utilities in the Symfony DomCrawler & Articrawler Extension
 *
 * @package FinalProject\Commands
 */
class HeadlineCommand extends Command {

    protected $sample;

    /**
     * Setup our Text method
     */
    protected function configure()
    {
        $this->setName('dom:headline')
            ->setDescription('Search the DOM for an article\'s headline.')
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
         * Download the content in a SourceResult object and Create a new Crawler
         */
        $web = Source::curl($url, \Config::curl());
        $html = new Articrawler($web->getSource());
        $s = new Scraper($html);

        if ($s->headline()->count()) {
            if ($dump) {
                foreach($s->headline() as $h)
                    $output->writeln($h->nodeName()." Score (".number_format($h->getScoreTotal("headline"),1). "): \t".$h->text());
            } else {
                $first = $s->headline()->first();
                $output->writeln($first->nodeName()." Score (".$first()->getScoreTotal("headline"). "): \t".$first->text());
            }
        } else {
            $output->writeln("Sorry, we couldn't find a headline.");
        }
    }

}