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
            $childlessCrawler = new AiCrawler(clone $this->getNode($position));
            $childlessCrawler->children()->each(function($node) use(&$childlessCrawler) {
                $childlessCrawler->getNode(0)->removeChild($node->getNode(0));
            });
            return $childlessCrawler;
        } else {
            return false;
        }
    }

    /**
     * Get multiple attributes from the node.
     *
     * @param array $attributes
     *
     * @return array
     */
    public function attributes(array $attributes = []) {
        if (!count($this)) {
            throw new \InvalidArgumentException('The current node list is empty.');
        }

        $node = $this->getNode(0);

        $assoc = [];
        foreach ($attributes as $attribute) {
            $assoc[$attribute] = $node->hasAttribute($attribute)
                ? $node->getAttribute($attribute) : null;
        }
        return $assoc;
    }

}