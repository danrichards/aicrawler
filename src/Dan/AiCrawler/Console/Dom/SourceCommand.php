<?php namespace Dan\AiCrawler\Console\Dom;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Dan\AiCrawler\Support\AiConfig;
use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Source;
use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;

/**
 * Run BFS on URL and provide plenty of options to the user for output.
 *
 * @package AiCrawler\Commands
 */
class SourceCommand extends Command {

    protected $config;

    /**
     * Setup our Commmand
     */
    protected function configure() {
        $this->config = new AiConfig();

        $this
            ->setName('dom:source')
            ->setHelp("e.g. php crawl dom:source http://www.example.com/")
            ->setDescription('Just output the html source so we can check what the scraper is getting.')
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'Enter a URL to download the source with CURL or file_get_contents.',
                'http://www.example.com/'
            );
    }

    /**
     * Output some nodes BFS style.
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
         * Setup a crawler and output
         */
        try {
            $data = Source::both($url, $this->config->get("curl"));
            $output->writeln($data->getSource());
        } catch (SourceNotFoundException $e) {
            $output->writeln("Unable to download the source with curl. ".$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $output->writeln("A crawler method was called with a bad argument. ".$e->getMessage());
        }
    }
}