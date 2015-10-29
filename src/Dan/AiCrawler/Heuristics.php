<?php

namespace Dan\AiCrawler;

use Dan\Core\Helpers\RegEx;

class Heuristics
{
    /**
     * TODO Instead of doing subset functions, consider another attribute for the scraper
     *
     * Functions that traverse additional nodes for requirements may optionally
     * specify more criteria with any of the functions in this list.
     *
     * @var array $subsetFunctions
     */
    public static $subsetFunctions = ['min_words', 'min_chars', 'words', 'all_words', 'attribute', 'attribute_values'];

    /**
     * @var array $defaults
     */
    public static $defaults = [
        'domain' => false,
        'min_words' => 0,
        'min_chars' => 0,
        'min_children' => 1,
        'attributes' => ['id', 'class', 'name', 'alt', 'title', 'value', 'label']
    ];

    /**
     * Node contains children that meet criteria given in args.
     *
     * @param AiCrawler $node
     * @param array $args 'elements' and min_child
     *
     * @return bool
     */
    public static function with_children(AiCrawler &$node, array $args = [])
    {
        $args['min_children'] = isset($args['min_children']) ? $args['min_children'] : self::$defaults['min_children'];
        $occurrences = [];
        $node->each(function($n) use (&$occurrences, $args) {
            $element = strtolower($n->nodeName());
            if (in_array($element, $args['elements']) && self::hasSubsetCriteria($n, $args)) {
                array_push($occurrences, $element);
            }
        });

        return count($occurrences) >= $args['min_children'];
    }

    /**
     * Run additional specified functions that are in the permitted subset
     * Functions list on a node. (used by rules that inspect subsequent nodes)
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    protected static function hasSubsetCriteria(AiCrawler &$node, array $args = [])
    {
        $criteria = array_intersect_key($args, self::$subsetFunctions);
        foreach($criteria as $func => $moreArgs) {
            if (method_exists(self, $func)) {
                if (! self::$func($node, $moreArgs)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Node is a paragraph element
     *
     * @param AiCrawler $node
     *
     * @param array $args
     * @return bool
     */
    public static function p(AiCrawler &$node, array $args = [])
    {
        return (strtolower($node->nodeName()) == 'p');
    }

    /**
     * Node is an anchor element with an optionally specified domain.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function a(AiCrawler &$node, array $args = [])
    {
        $domain = isset($args['domain']) ? $args['domain'] : self::$defaults['domain'];

        if ($node->nodeName() == 'a') {
            if ($domain) {
                $href = $node->attr('href');
                if (! empty($href) && strpos(strtolower(RegEx::urlDomain($href)), strtolower($domain)) !== false) {
                    return true;
                }
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Node is an element of types supplied.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function element(AiCrawler &$node, array $args = [])
    {
        return in_array(strtolower($node->nodeName()), $args['elements']);
    }

    /**
     * Node has an attribute of a type in list
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function attribute(AiCrawler &$node, array $args = [])
    {
        $attributes = isset($args['attributes']) ? $args['attributes'] : self::$defaults['attributes'];
        $matches = isset($args['matches']) ? $args['matches'] : "any";

        $attributesFound = array_diff($node->getAttributes($attributes), [null]);

        switch ($matches) {
            case "all":
                return count($attributes) == count($attributesFound);
            case "any":
                return boolval(count($attributesFound));
            case "none":
                return ! boolval(count($attributesFound));
            default:
                return count($attributesFound) >= ((int) $matches);
        }
    }

    /**
     * Node has an attributes with values, matching special criteria.
     *
     * If values is an associative array, the attributes will be checked against
     * a single value.
     *
     * If values is a numeric array, then we'll match each attribute against
     * any of the values provided.
     *
     * We may specify if the matches any, all, none, or # attributes.
     *
     * We may specify how many matches are required with the matches param.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function attribute_values(AiCrawler &$node, array $args)
    {
        $attributes = isset($args['attributes']) ? $args['attributes'] : self::$defaults['attributes'];
        $matches = isset($args['matches']) ? $args['matches'] : "any";
        $search = (array) $args['values'];

        $attributesFound = $node->getAttributes($attributes);
        $attributesMatching = [];

        if (array_keys($search)[0] === 0) {
            foreach($attributesFound as $attr => $value) {

            }
        } else {

        }

        /**
         * Only return true when all, any, none, or a numerically specified
         * amount of matches are found.
         */
        switch ($matches) {
            case "all":
                break;
            case "any":
                break;
            case "none":
                break;
            default:

        }
    }

    /**
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function words(AiCrawler &$node, array $args)
    {
        return true;
    }

    /**
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function min_characters(AiCrawler &$node, array $args)
    {
        return true;
    }

    /**
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function min_words(AiCrawler &$node, array $args)
    {
        return true;
    }

    /**
     * Awards points for each element in the DOM that shares a frequency of
     * similar words.
     *
     * @param AiCrawler &$node
     * @param array $args
     *
     * @return bool
     */
    public static function similar_words(AiCrawler &$node, array $args)
    {
        return true;
    }

    /**
     * Look in a node for the largest image.
     *
     * Optionally set `by` width, height, resolution, size.
     *
     * Optionally require that the image be on specific a domain.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function largest_image(AiCrawler &$node, array $args)
    {
        return true;
    }

    /**
     * Look in a node for the smallest image.
     *
     * Optionally set `by` width, height, resolution, size.
     *
     * Optionally require that the image be on specific a domain.
     *
     * @param AiCrawler $node
     * @param array $args
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public function smallest_image(AiCrawler &$node, array $args)
    {
        return true;
    }

    /**
     * Look in a node for an image that meets specific criteria.
     *
     * Optionally set max_size, min_size, max_width, max_height, or
     * max_resolution.
     *
     * Optionally require that the image be on specific a domain.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public function specific_image(AiCrawler &$node, array $args)
    {
        return true;
    }

    /**
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function num_children(AiCrawler &$node, array $args)
    {
        $args['relation'] = "children";
        return self::num_relation($node, $args);
    }

    /**
     * Node a siblings of a
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function num_siblings(AiCrawler &$node, array $args)
    {
        $args['relation'] = "siblings";
        return self::num_relation($node, $args);
    }

    /**
     * Node has at least so many parents.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function num_parents(AiCrawler &$node, array $args)
    {
        $args['relation'] = "parents";
        return self::num_relation($node, $args);
    }

    /**
     * Node has at least so many of a relation.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    private static function num_relation(Aicrawler &$node, $args)
    {
        $relation = $args['relation'];
        $matches = isset($args['matches']) ? $args['matches'] : "any";
        $elements = $args['elements'] ? (array) $args['elements'] : false;
        $total = 0;
        $hits = 0;
        $node->$relation->each(function($n) use ($elements, &$total, &$hits) {
            if ($elements) {
                $hits += (in_array($n->nodeName(), $elements) ? 1 : 0;
            } else {
                $hits ++;
            }
            $total ++;
        });

        switch ($matches) {
            case "all":
                return $total == $hits;
            case "any":
                return boolval($hits);
            case "none":
                return ! boolval($hits);
            default:
                return $hits >= (int) $matches;
        }
    }

    /**
     * Augment a node's scoring based on two or previous rules hitting.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function on_hitting(AiCrawler &$node, array $args)
    {
        $item = $args['item'];
        if (! $node->hasItem($item)) {
            return false;
        }
        $rules = (array) $args['rules'];
        $matches = isset($args['matches']) ? $args['matches'] : "any";
        $hits = [];
        foreach ($rules as $rule) {
            if ($node->hasDataPoint($item, $rule) && $node->dataPoint($item, $rule) > 0) {
                $hits[] = $rule;
            }
        }
        switch ($matches) {
            case "all":
                return count($hits) == count($rules);
            case "any":
                return boolval(count($hits));
            case "none":
                return ! boolval(count($hits));
            default:
                return count($hits) >= (int) $matches;
        }
    }

    /**
     * Augment a node's scoring based on two or previous rules missing.
     *
     * Optionally count null scores as missing.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function on_missing(AiCrawler &$node, array $args)
    {
        $item = $args['item'];
        if (! $node->hasItem($item)) {
            return false;
        }
        $rules = (array) $args['rules'];
        $matches = isset($args['matches']) ? $args['matches'] : "any";
        $misses = [];
        foreach ($rules as $rule) {
            if (! $node->hasDataPoint($item, $rule) || $node->dataPoint($item, $rule) <= 0) {
                $misses[] = $rule;
            }
        }
        switch ($matches) {
            case "all":
                return count($misses) == count($rules);
            case "any":
                return boolval(count($misses));
            case "none":
                return ! boolval(count($misses));
            default:
                return count($misses) >= (int) $matches;
        }
    }
}