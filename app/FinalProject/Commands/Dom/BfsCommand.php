<?php namespace FinalProject\Commands\Dom;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use FinalProject\Support\Finder;
use FinalProject\Support\Source;
use FinalProject\Support\Articrawler;

/**
 * Class TagsCommand
 *
 * @package FinalProject\Commands
 */
class BfsCommand extends Command {

    /**
     * Setup our Greeting
     */
    protected function configure() {
        $this
            ->setName('dom:bfs')
            ->setDescription('Do a bread-first search of the DOM and output the nodes.')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'Enter a URL to inspect.'
            )
            ->addOption(
                'show',
                null,
                InputArgument::OPTIONAL,
                'Provide additional detail.'
            )
            ->addOption(
                'stop',
                null,
                InputArgument::OPTIONAL,
                'When should we stop? e.g. 3 (shows the first three levels)'
            )
            ->addOption(
                'only',
                null,
                InputArgument::OPTIONAL,
                'Only traverse certain tags? e.g. html (auto included), body (auto included), header, footer, div (span, script, etc...are ignored)'
            )
            ->addOption(
                'except',
                null,
                InputArgument::OPTIONAL,
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
        $url = $input->getArgument('url');
        $extra['show'] = is_null($input->getOption('show')) ? null : explode(",", $input->getOption('show'));
        $extra['only'] = is_null($input->getOption('only')) ? null : array_merge(explode(",", $input->getOption('only')), ['html', 'body']);
        $extra['except'] = is_null($input->getOption('except')) ? null : explode(",", $input->getOption('except'));
        // How deep should we go?
        $stop = is_null($input->getOption('stop')) ? null : $input->getOption('stop');

        $data = Source::curl($url, \Config::curl());
        $crawler = new Articrawler($data->getSource());
        $text = Finder::bfsOutput($crawler, $extra, $stop);
        $output->writeln($text);
    }

}