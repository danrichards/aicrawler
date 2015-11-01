<?php

namespace Dan\AiCrawler;

use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;
use Dan\Core\Support\Traits\Extra;

/**
 * Class AiCrawler
 *
 * @package AiCrawler
 * @author Dan Richards <danrichardsri@gmail.com>
 */
class AiCrawler extends SymfonyCrawler {

    /**
     * Essentially, an associative array of associative arrays.
     *
     * e.g.
     * [
     *      'headline' => [
     *          'datapoint' => 'value'
     *      ]
     * ]
     *
     * @var $scores
     */
    use Scoreable;

    /**
     * Same as above, except the inner array has extra data gathered from our Heuristics.
     *
     * @var $extra
     * @contains methods setExtra([$key|[assoc], $data) and getExtra($key|[$keys])
     */
    use Extra;

    /**
     * Call the parent constructor and get some additional stuff ready
     *
     * @param null $node
     * @param null $currentUri
     * @param null $baseHref
     */
    function __construct($node = null, $currentUri = null, $baseHref = null) {
        parent::__construct($node, $currentUri, $baseHref);
    }

}