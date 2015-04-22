<?php
namespace FinalProject\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

use FinalProject\Samples;
use FinalProject\Getter;

/**
 * Class TagsCommand
 *
 * @package FinalProject\Commands
 */
class PathCommand extends Command {

    /**
     * Setup our Greeting
     */
    protected function configure()
    {
        $this
            ->setName('dom:path')
            ->setDescription('Use heuristics to find the content of an article.')
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
//        http://www.huffingtonpost.com/2015/04/15/bernie-sanders-hillary-clinton_n_7074500.html
        $url = $input->getArgument('url');
        $extra['show'] = is_null($input->getOption('show')) ? null : explode(",", $input->getOption('show'));
        $extra['only'] = is_null($input->getOption('only')) ? null : array_merge(explode(",", $input->getOption('only')), ['html', 'body']);
        $extra['except'] = is_null($input->getOption('except')) ? null : explode(",", $input->getOption('except'));
        var_dump($extra['only']);
        // How deep should we go?
        $stop = is_null($input->getOption('stop')) ? null : $input->getOption('stop');

        $data = Getter::curl($url, \Config::curl());
        $crawler = new Crawler($data->source);
        $text = Samples::bfs($crawler, $extra, $stop);
        $output->writeln($text);
    }
}