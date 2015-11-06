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
    public function __construct($node = null, $currentUri = null, $baseHref = null) {
        parent::__construct($node, $currentUri, $baseHref);
    }

    /**
     * Some heuristics may need to augment the current node tree to be useful.
     * Since we're using a bunch of objects and references. We'll need to make
     * a "copy" to augment so we don't damage our original.
     *
     * @see CharactersTests::<any_method>()
     * @see WordTests::<any_method>()
     *
     * @param int $position
     *
     * @return bool|AiCrawler
     */
    public function createChildlessSubCrawler($position = 0)
    {
        $subDOMElement = $this->getNode($position) ?: false;
        if ($subDOMElement) {
            $subCrawler = new static($subDOMElement);
            $subCrawler->children()->each(
                function (AiCrawler $crawler) {
                    foreach ($crawler as $node) {
                        $node->parentNode->removeChild($node);
                    }
                }
            );
            return $subCrawler;
        } else {
            return false;
        }
    }

}