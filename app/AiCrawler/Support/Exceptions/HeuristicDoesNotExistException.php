<?php namespace AiCrawler\Support\Exceptions;

/**
 * Something to catch in case someone tries to scrape() before defining their Heuristics
 *
 * @package AiCrawler\Support
 */
class HeuristicDoesNotExistException extends \Exception{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, \Exception $previous = null) {
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}