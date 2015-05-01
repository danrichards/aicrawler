<?php namespace AiCrawler\Support;

use Illuminate\Support\Collection;

/**
 * Let's keep our considerations organized
 *
 * Class Considerations
 * @package AiCrawler\Support
 */
class Considerations extends Collection {

    /**
     * Overload sort to sort by score
     *
     * @param callable $callable
     */
    public function sortByScore($classification) {
        parent::sort(function(AiCrawler $a, AiCrawler $b) use ($classification) {
            if ($a->getScore($classification) == $b->getScore($classification))
                return 0;
            return ($a->getScore($classification) < $b->getScore($classification)) ? 1 : -1;
        });
        return $this;
    }

    /**
     * Count how many tags exists as children or some other classification relative to the node provided,
     * If no node is provided, use $this
     *
     * @param $node
     * @param $type
     * @param $filter
     * @param $children
     */
    public function getTagsCount($tags) {
        $count = 0;

        /**
         * Count our tags
         */
        $this->each(function($n, $i) use ($tags, &$count) {
            $name = $n->nodeName();
            if (is_array($tags) && in_array($name, $tags))
                $count ++;
            elseif($name == $tags)
                $count++;
        });

        return $count;
    }

}