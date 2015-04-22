#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use FinalProject\Commands\GreetCommand;
use FinalProject\Commands\Test\TextCommand;
use FinalProject\Commands\Dom\BfsCommand;
use FinalProject\Commands\Dom\InspectCommand;
use FinalProject\Commands\Dom\HeadlineCommand;

$application = new Application();
$application->add(new GreetCommand());
$application->add(new BfsCommand());
$application->add(new TextCommand());
$application->add(new InspectCommand());
$application->add(new HeadlineCommand());
$application->run();