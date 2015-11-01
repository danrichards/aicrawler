<?php

namespace Dan\AiCrawler;

use Dan\Core\Helpers\RegEx;
use InvalidArgumentException;

/**
 * Class Heuristics
 *
 * @package Dan\AiCrawler
 * @author Dan Richards <danrichardsri@gmail.com>
 *
 * Heuristics is a bunch of static methods that return boolean values. We may
 * build creative tools that traverse a node structure and score nodes using
 * these static methods without memory getting out of hand.
 */
class Heuristics
{
    /**
     * Our `with_*` methods allow us to pass through subsequent rules for the
     * respective nodes they inspect.
     *
     * @var array $subsetFunctions
     */
    public static $subsetFunctions = [
        'p' => 'p',
        'a' => 'a',
        'element' => 'element',
        'num_chars' => 'num_chars',
        'num_words' => 'num_words',
        'num_sentences' => 'num_sentences',
        'words' => 'words',
        'attribute' => 'attribute',
        'attribute_values' => 'attribute_values'
    ];

    /**
     * @var array $defaults
     */
    public static $defaults = [
        'matches' => 'any',
        'domain' => false,
        'num_words' => 0,
        'num_chars' => 0,
        'num_children' => 1,
        'attributes' => ['id', 'class', 'name', 'alt', 'title', 'value', 'label'],
        'elements' => ['p', 'blockquote', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'article', 'content'],
        'punctuation' => ['?',".","!"],
        'suffixes' => ['s', 'es', 'ed', 'ing', 'ly', 'er', 'or', 'ion', 'tion', 'ation', 'ition', 'ible', 'able', 'al', 'ial', 'y', 'ness', 'ity', 'ty', 'ment', 'ic', 'ous', 'eous', 'ious', 'en', 'er', 'ive', 'ative', 'itive', 'ful', 'less', 'est']
    ];

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
        $domain = isset($args['domain'])
            ? $args['domain'] : self::$defaults['domain'];

        if ($node->nodeName() == 'a') {
            if ($domain) {
                $href = $node->attr('href');
                if (! empty($href)) {
                    $domainHref = strtolower(RegEx::urlDomain($href));
                    return strpos($domainHref, strtolower($domain)) !== false;
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
        $nodeElement = strtolower($node->nodeName());
        $elements = isset($args['elements']) ? (array) $args['elements'] : [];
        $regex = isset($args['regex']) && $args['regex'];
        if ($regex) {
            foreach($elements as $element) {
                if (preg_match($element, $nodeElement)) {
                    return true;
                }
            }
            return false;
        } else {
            return in_array($nodeElement, $elements);
        }
    }

    /**
     * Node has an attribute of a type in a list.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function attribute(AiCrawler &$node, array $args = [])
    {
        $matches = isset($args['matches'])
            ? $args['matches'] : self::$defaults['matches'];
        $attributes = isset($args['attributes'])
            ? (array) $args['attributes'] : self::$defaults['attributes'];

        $attributesFound = [];
        foreach ($attributes as $a) {
            $attributesFound[$a] = $node->attr($a);
        }
        $attributesFound = array_diff($attributesFound, [null]);

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
     * a the associative value.
     *
     * If values is a numeric array, then we'll match each attribute against
     * any of the values provided.
     *
     * We may specify if it matches any, all, none, or # attributes.
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
        $attributes = isset($args['attributes'])
            ? $args['attributes'] : self::$defaults['attributes'];
        $matches = isset($args['matches'])
            ? $args['matches'] : self::$defaults['matches'];
        $case_sensitive = isset($args['case_sensitive']) &&
            $args['case_sensitive'];
        $regex = isset($args['regex']) && $args['regex'];

        if (! isset($args['values'])) {
            throw new InvalidArgumentException(
                "`rules` is a require arg."
            );
        }
        $values = (array) $args['values'];
        $numeric = isset($values[0]);

        $attributesFound = [];
        foreach ($attributes as $a) {
            $attributesFound[$a] = $case_sensitive
                ? $node->attr($a) : strtolower($node->attr($a));
        }
        $attributesFound = array_diff($attributesFound, [null]);
        $hits = 0;
        /**
         * Each attribute may carry any of the values we provided.
         */
        if ($numeric) {
            foreach($attributes as $attr) {
                if (isset($attributesFound[$attr])) {
                    foreach($values as $v) {
                        if ($regex && preg_match($v, $attributesFound[$attr])) {
                            $hits ++;
                            break;
                        } elseif ($v == $attributesFound[$attr]) {
                            $hits ++;
                            break;
                        }
                    }
                }
            }
        /**
         * Each attribute must match its associated value.
         */
        } else {
            foreach($values as $attr => $value) {
                if (isset($attributesFound[$attr])) {
                    if ($regex && preg_match($value, $attributesFound[$attr])) {
                        $hits ++;
                    } elseif($value == $attributesFound[$attr]) {
                        $hits ++;
                    }
                }
            }
        }

        /**
         * Only return true when all, any, none, or a numerically specified
         * amount of matches are found.
         */
        switch ($matches) {
            case "all":
                return $numeric ? $hits == count($attributes) : $hits == count($values);
            case "any":
                return boolval($hits);
            case "none":
                return ! boolval($hits);
            default:
                return $hits >= (int) $matches;
        }
    }

    /**
     * @param AiCrawler $node
     * @param array $args
     *
     * @todo A modifier for words that start / end with characters?
     * @todo A modifier for characters must be capitalized?
     *
     * @return bool
     */
    public static function characters(AiCrawler &$node, array $args)
    {
        return strlen(RegEx::removeExtraneousWhitespace($node->text()));
    }

    /**
     * Node has words exists within a node.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @todo Consider noise at the endings of words. e.g.
     * @see http://grammar.about.com/od/words/a/comsuffixes.htm
     * @see https://www.learnthat.org/pages/view/suffix.html
     *
     * @return bool
     */
    public static function words(AiCrawler &$node, array $args)
    {
        return str_word_count(RegEx::removeExtraneousWhitespace($node->text()));
    }

    /**
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function sentences(AiCrawler &$node, array $args)
    {
        $total = 0;
        $sentences = explode('.', rtrim($node->text(), '.'));
        foreach($sentences as $s) {
            $first = mb_substr($s, 0, 1, "UTF-8");
            $upper = mb_strtolower($first, "UTF-8") != $first;
            $total += $upper ? 1 : 0;
        }
        return $total;
    }

    /**
     * Look in a node for an image that meets specific criteria.
     *
     * Optionally set max_size, min_size, max_width, max_height, or
     * max_resolution, mime_type.
     *
     * Optionally require that the image be on specific a domain.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public function image(AiCrawler &$node, array $args)
    {
        return true;
    }

    /**
     * Node has previously scored on previous rules.
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
            throw new InvalidArgumentException(
                "An item for the data point is required."
            );
        }
        if (! isset($args['rules'])) {
            throw new InvalidArgumentException(
                "`rules` is a require arg."
            );
        }

        $matches = isset($args['matches'])
            ? $args['matches'] : self::$defaults['matches'];
        $rules = (array) $args['rules'];
        $hits = [];
        foreach ($rules as $rule) {
            if ($node->hasDataPoint($item, $rule) &&
                $node->dataPoint($item, $rule) > 0)
            {
                $hits++;
            }
        }

        switch ($matches) {
            case "all":
                return $hits == count($rules);
            case "any":
                return boolval($hits);
            case "none":
                return ! boolval($hits);
            default:
                return $hits >= (int) $matches;
        }
    }

    /**
     * Node has not previously scored on previous rule(s).
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
            throw new InvalidArgumentException(
                "An item for the data point is required."
            );
        }
        if (! isset($args['rules'])) {
            throw new InvalidArgumentException(
                "`rules` is a require arg."
            );
        }

        $matches = isset($args['matches'])
            ? $args['matches'] : self::$defaults['matches'];
        $rules = (array) $args['rules'];
        $misses = 0;
        foreach ($rules as $rule) {
            if (! $node->hasDataPoint($item, $rule) ||
                $node->dataPoint($item, $rule) <= 0)
            {
                $misses++;
            }
        }

        switch ($matches) {
            case "all":
                return $misses == count($rules);
            case "any":
                return boolval($misses);
            case "none":
                return ! boolval($misses);
            default:
                return $misses >= (int) $matches;
        }
    }

    /**
     * Node contains related nodes that pass on rules provided. Add any of the
     * rules in self::$subsetFunctions as args with their respect args as an
     * associative array.
     *
     * $args example:
     *
     * [
     *     // some rule
     *     'num_words' => [
     *         // args for some rule
     *         'matches' => '3'
     *     ]
     *      // some other rule
     *     'elements' => [
     *          // args for some other rule
     *         'matches' => 'any'
     *         'elements' => ['p', 'blockquote', 'h2', 'h3', 'ul', 'ol'],
     *     ],
     *     // you can run the same rule again with different args
     *     'elements2' => [
     *         'matches' => 'none'
     *         'elements' => ['input', 'form', 'script'],
     *     ],
     *     // What outcome is required of all the rules we ran?
     *     'matches' => 'all'
     * ]
     *
     * @param $relation
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     * @see self::$subsetFunctions
     *
     */
    protected static function with_relation($relation, AiCrawler &$node, array $args = [])
    {
        $matches = isset($args['matches'])
            ? $args['matches'] : self::$defaults['matches'];
        $rules = array_intersect_key($args, self::$subsetFunctions);

        if (! in_array($relation, [])) {
            throw new InvalidArgumentException(
                "Relation: {$relation} is not a valid relation, use `children`,
                `siblings`, or `parents`."
            );
        }

        foreach ($rules as $rule) {
            $method = preg_replace('/[0-9]+/', '', $rule);
            if (! method_exists(self, $method)) {
                throw new InvalidArgumentException(
                    "Rule: $method is not a valid subset function. Use ".
                    implode(", ", self::$subsetFunctions)."."
                );
            }
        }

        $related = 0;
        $node->$relation()->each(function($n) use (&$related) { $related++; });

        $ruleHits = [];
        foreach ($rules as $rule => $args) {
            $method = preg_replace('/[0-9]+/', '', $rule);
            $node->$relation()->each(function($n) use (&$ruleHits, $rule, $method, $args) {
                if (self::$method($n, $args)) {
                    $ruleHits[$rule]++;
                }
            });
        }

        /**
         * There are many cases for constraining success.
         */
        switch ($matches) {
            /**
             * Did all nodes pass all the rules??
             */
            case "all":
                if (count($ruleHits) == count($rules)) {
                    foreach($ruleHits as $rule => $hits) {
                        if ($hits != $related) {
                            return false;
                        }
                    }
                    return true;
                }
                return false;

            /**
             * Did all nodes pass at least one rule?
             */
            case "all-any":
                return count($rules) == count($ruleHits);

            /**
             * Did all nodes pass none of the rules?
             */
            case "all-none":
            case "none":
                return empty($ruleHits);

            /**
             * Did any of the nodes pass any of the rules?
             */
            case "any":
                return ! empty($ruleHits);

            /**
             * Did any of the nodes pass all of the rules?
             */
            case "any-all":
                foreach($ruleHits as $rule => $hits) {
                    if ($hits == $related) {
                        return true;
                    }
                }
                return false;

            /**
             * Did any of the nodes pass none of the rules?
             */
            case "any-none":
                return count($ruleHits) < count($rules);

            /**
             * Oh snap, you just changed the game!
             */
            default:
                if (is_array($matches)) {
                    /**
                     * SPECIAL: Did specific rules hit on anything?
                     *
                     * ['rule', 'rule2', ...]
                     */
                    if (isset($matches[0])) {
                        $ruleHitsKeys = array_keys($ruleHits);
                        $matchesRHK = array_intersect($ruleHitsKeys, $matches);
                        return count($matchesRHK) == count($matches);
                    /**
                     * Pass an associative array as follows:
                     *
                     * [
                     *     'rule' => all|any|none|#
                     *     'rule2' => all|any|none|#
                     *     ...
                     * ]
                     *
                     * SPECIAL: Did specific rules hit a specific way?
                     */
                    } else {
                        foreach($matches as $rule => $ruleMatches) {
                            switch($ruleMatches) {
                                case "all":
                                    if ($ruleHits[$rule] < $related) {
                                        return false;
                                    }
                                    break;
                                case "any":
                                    if (! isset($ruleHits[$rule])) {
                                        return false;
                                    }
                                case "none":
                                    if (isset($ruleHits[$rule])) {
                                        return false;
                                    }
                                default:
                                    if (!isset($ruleHits[$rule]) ||
                                        $ruleHits[$rule] < $ruleMatches) {
                                        return false;
                                    }
                            }
                        }
                        return true;
                    }
                }

                /**
                 * Default a number of rules hit on anything?
                 */
                return count($ruleHits) >= $matches;
        }
    }

    /**
     * Node has children that meet rules requirements.
     *
     * @see self::with_relation()
     *
     * @param AiCrawler $node
     * @param array $args matches, subsetFunctions
     *
     * @return bool
     */
    public static function with_children(AiCrawler &$node, array $args = [])
    {
        return self::with_relation("children", $node, $args);
    }

    /**
     * Node has siblings that meet rules requirements.
     *
     * @see self::with_relation()
     *
     * @param AiCrawler $node
     * @param array $args matches, subsetFunctions
     *
     * @return bool
     */
    public static function with_siblings(AiCrawler &$node, array $args = [])
    {
        return self::with_relation("siblings", $node, $args);
    }

    /**
     * Node has parents that meet rules requirements.
     *
     * @see self::with_relation()
     *
     * @param AiCrawler $node
     * @param array $args matches, subsetFunctions
     *
     * @return bool
     */
    public static function with_parents(AiCrawler &$node, array $args = [])
    {
        return self::with_relation("parents", $node, $args);
    }

}