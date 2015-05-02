<?php namespace Dan\AiCrawler\Commands\Dom;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Finder;
use Dan\AiCrawler\Support\Source;
use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;

/**
 * Run BFS on URL and provide plenty of options to the user for output.
 *
 * @package AiCrawler\Commands
 */
class BfsCommand extends Command {

    /**
     * Setup our Commmand
     */
    protected function configure() {
        $this
            ->setName('dom:bfs')
            ->setDescription('Get details about the DOM using breadth-first search.')
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'Enter a URL to inspect.',
                'http://www.example.com/'
            )
            ->addOption(
                'show',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Try any combination of all(a), parents(p), children(c), siblings(s), depth(d), words(w), sentences(sn), paragraphs(pg), text(t) or html(h).'
            )
            ->addOption(
                'stop',
                's',
                InputOption::VALUE_OPTIONAL,
                'When should we stop? e.g. 3 (shows the first three levels)'
            )
            ->addOption(
                'only',
                'o',
                InputOption::VALUE_OPTIONAL,
                'Only traverse certain tags? e.g. html (auto included), body (auto included), header, footer, div (span, script, etc...are ignored)'
            )
            ->addOption(
                'except',
                'e',
                InputOption::VALUE_OPTIONAL,
                'Search all tags except these and their children? e.g. span, script (omits these tags)'
            );
    }

    /**
     * Say Hello
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        /**
         * What to search?
         */
        $url = $input->getArgument('url');
        if ($url == 'http://www.example.com/')
            $output->writeln("A URL argument was not provided, http://www.example.com will be used.");

        /**
         * What do display?
         */
        $extra = [];
        $extra['show'] = explode(",", regex_remove_whitespace($input->getOption('show')));
        $extra['only'] = !is_null($input->getOption('only'))
            ? array_merge(explode(",", regex_remove_whitespace($input->getOption('only'))), ['html', 'body'])
            : $input->getOption('only');
        $extra['except'] = !is_null($input->getOption('except'))
            ? explode(",", regex_remove_whitespace($input->getOption('except')))
            : $input->getOption('except');

        /**
         * How deep should we go?
         */
        $stop = $input->getOption('stop');

        /**
         * Setup a crawler and output
         */
        try {
            $data = Source::both($url, \Config::curl());
            $crawler = new AiCrawler($data->getSource());
            $text = Finder::bfsOutput($crawler, $extra, $stop);
            $output->writeln($text);
        } catch (SourceNotFoundException $e) {
            $output->writeln("Unable to download the source with curl. ".$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $output->writeln("A crawler method was called with a bad argument. ".$e->getMessage());
        }
    }

}