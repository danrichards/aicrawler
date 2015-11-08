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
 *
 * @todo first() method ~ parent node's first [child, parent, sibling]
 * @todo last() method ~ parent node's last [child, parent, sibling]
 * @todo nth_child method ~ parent node's n-th [child, parent, sibling]
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
        'characters' => 'characters',
        'words' => 'words',
        'sentences' => 'sentences',
        'p' => 'p',
        'a' => 'a',
        'element' => 'element',
        'attribute' => 'attribute',
        'attribute_values' => 'attribute_values'
    ];

    /**
     * Global defaults that apply to several (not all) functions.
     *
     * Want to make your own defaults? Extend Heuristics.
     *
     * @var array
     */
    protected static $defaults = [
        'matches' => 'any',
        'case_sensitive' => false,
        'position' => 0,
        'children' => false,
        'regex' => false,
    ];

    /**
     * Defaults for sentences()
     *
     * @var array $sentences
     */
    protected static $characters = [
        'characters' => true,
    ];

    /**
     * Defaults for words()
     *
     * @var array $words
     */
    protected static $words = [
        'words' => true,
    ];

    /**
     * Defaults for punctuation()
     *
     * @var array $punctuation
     */
    protected static $punctuation = [
        'delimiters' => "!?.",
        'marks' => '!?.',
    ];

    /**
     * Defaults for sentences()
     *
     * @var array $sentences
     */
    protected static $sentences = [
        'sentences' => false,           // search keyword / pattern
        'min_words' => 1,               // for a valid sentence.
    ];

    /**
     * Defaults for questions()
     *
     * @var array $questions
     */
    protected static $questions = [
        'questions' => false,           // search keyword / pattern
        'min_words' => 1,               // for a valid sentence.
    ];

    /**
     * Defaults for exclamatory()
     *
     * @var array $exclamatory
     */
    protected static $exclamatory = [
        'exclamatory' => false,         // search keyword / pattern
        'min_words' => 1,               // for a valid sentence.
    ];

    /**
     * Defaults for a()
     *
     * @var array $a
     */
    protected static $a = [
        'domain' => false,
    ];

    /**
     * Defaults for img()
     *
     * @var array $img
     */
    protected static $img = [];

    /**
     * Defaults for elements()
     *
     * @var array $sentences
     */
    protected static $elements = [];

    /**
     * Defaults for attributes()
     *
     * @var array $attributes
     */
    protected static $attributes = [
        'attributes' => ['id', 'class', 'name', 'alt', 'title', 'value', 'label'],
    ];

    /**
     * Defaults for after_hitting()
     *
     * @var array $after_hitting
     */
    protected static $after_hitting = [];

    /**
     * Defaults for after_missing()
     *
     * @var array $after_missing
     */
    protected static $after_missing = [];

    /**
     * Defaults for on()
     *
     * @var array $on
     */
    protected static $on = [];

    /**
     * Defaults for children()
     *
     * @var array $children
     */
    protected static $children = [];

    /**
     * Defaults for siblings()
     *
     * @var array $siblings
     */
    protected static $siblings = [];

    /**
     * Defaults for parents()
     *
     * @var array $parents
     */
    protected static $parents = [];

    /**
     * Defaults for attribute_values()
     *
     * @var array
     */
    protected static $attribute_values = [
        'attributes' => ['id', 'class', 'name', 'alt', 'title', 'value', 'label'],
    ];

    /**
     * Node has special ascii character patterns.
     *
     * @args characters
     *     Default: true
     *     bool(true) ~ any ascii character match.
     *     int ~ strlen of text greater. The 'matches' param is ignored.
     *     string ~ a string of ascii characters.
     *     array ~ numerically indexed array of chars.
     *     assoc array ~ keys: chars, values: min occurrences required.
     * @args case_sensitive
     *     Default: false
     *     When true, characters given assumed lowercase.
     * @args children
     *     Default: false
     *     Consider text in child nodes.
     * @args position
     *     Default: 0
     *     Scrapers should auto include this param.
     * @args matches
     *     Default: any
     *     The words arg must be string, array, assoc array.
     *     You may require all, any, none, or a numeric amount of matches.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function characters(AiCrawler &$node, array $args)
    {
        $characters = self::arg($args, 'characters');
        $case_sensitive = self::arg($args, 'case_sensitive');
        $children = self::boolean($args, 'children');
        $position = self::arg($args, 'position');
        $matches = self::arg($args, 'matches');

        $text = self::text($node, $position, $children, $case_sensitive, true);

        /**
         * There is no text, we can figure this one out quickly.
         */
        if ($text == "") {
            return ($matches === 'none' || $matches === 0);
        /**
         * Just count the characters.
         */
        } elseif ($characters === true || is_int($characters)) {
            return strlen($text) > (int) $characters;
        }

        /**
         * Build the ASCII requirements for our count_chars()
         */
        if (is_array($characters) && ! isset($characters[0])) {
            $assoc = true;
            $keys = array_keys($characters);
            $ascii = array_combine(array_map('ord', $keys), $characters);
        } elseif (is_array($characters)) {
            $assoc = false;
            $ascii = array_map('ord', $characters);
            $ascii = array_fill_keys($ascii, 1);
        } else {
            $assoc = false;
            $ascii = array_fill_keys(array_map('ord', str_split($characters)), 1);
        }

        /**
         * Special cases are observed for associative arrays.
         */
        $counts = count_chars($text, 1);
        switch (true) {
            case $matches === "all":
                if (count(array_intersect_key($ascii, $counts)) < count($ascii)) {
                    return false;
                }
                if ($assoc) {
                    foreach ($ascii as $ord => $occurrences) {
                        if ($counts[$ord] < $ascii[$ord]) {
                            return false;
                        }
                    }
                }
                return true;
            case $matches === "any":
                foreach ($ascii as $ord => $occurrences) {
                    if (isset($counts[$ord]) && $counts[$ord] >= $ascii[$ord]) {
                        return true;
                    }
                }
                return false;
            case $matches === "none":
            case $matches === 0:
                return empty(array_intersect_key($ascii, $counts));
            default:
                if ($assoc) {
                    $matchCount = 0;
                    foreach ($ascii as $ord => $occurrences) {
                        if (isset($counts[$ord]) && $counts[$ord] >= $ascii[$ord]) {
                            if (++$matchCount >= $matches) {
                                return true;
                            }
                        }
                    }
                    return false;
                }
                return count(array_intersect_key($ascii, $counts)) >= $matches;
        }
    }

    /**
     * Node has special word patterns.
     *
     * @args words
     *     Default: true
     *     bool(true) ~ any character match.
     *     int ~ num words is at least. The 'matches' param is ignored.
     *     string ~ a string of ascii characters.
     *     array ~ numerically indexed array of chars.
     *     assoc array ~ keys: chars, values: min occurrences required.
     * @args case_sensitive
     *     Default: false
     *     When true, words given assumed lowercase.
     * @args regex
     *     Default: false
     *     When true, evaluate words as regular expressions.
     * @args children
     *     Default: false
     *     Consider text in children nodes.
     * @args position
     *     Default: 0
     *     Scrapers should auto include this param.
     * @args matches
     *     The words arg must be string, array, assoc array.
     *     You may require all, any, none, or a numeric amount of matches.
     *
     * @param AiCrawler $node
     * @param array $args ['children']
     *        Default: false. Consider text in children nodes.
     * @return bool
     *
     */
    public static function words(AiCrawler &$node, array $args)
    {
        $words = self::arg($args, 'words');
        $case_sensitive = self::boolean($args, 'case_sensitive');
        $regex = self::boolean($args, 'regex');
        $children = self::boolean($args, 'children');
        $position = self::arg($args, 'position');
        $matches = self::arg($args, 'matches');

        $text = self::text($node, $position, $children, $case_sensitive);

        /**
         * There is no text, we can figure this one out quickly.
         */
        if ($text == "") {
            return ($matches === 'none' || $matches === 0);
        /**
         * Just count the words.
         */
        } elseif ($words === true || is_int($words)) {
            return str_word_count($text) >= (int) $words;
        }

        /**
         * Words to become an array.
         */
        if (! is_array($words)) {
            $words = explode(" ", trim((string) $words));
        }

        /**
         * Build the word requirement of 1 for numeric array()
         */
        $assoc = ! isset($words[0]);
        $words = $assoc ? $words : array_fill_keys($words, 1);

        /**
         * Count them up!
         */
        $counts = [];
        if ($regex) {
            foreach ($words as $word => $match) {
                $counts[$word] = preg_match_all($word, $text);
            }
        } else {
            foreach ($words as $word => $match) {
                $counts[$word] = substr_count($text, $word, 0);
            }
        }

        /**
         * Special cases are observed for associative arrays.
         */
        switch (true) {
            case $matches === "all":
                if (! $assoc) {
                    return array_search(0, $counts, true) === false;
                }
                foreach ($counts as $word => $occurrences) {
                    if ($occurrences < $words[$word]) {
                        return false;
                    }
                }
                return true;
            case $matches === "any":
                foreach ($counts as $word => $occurrences) {
                    if ($occurrences >= $words[$word]) {
                        return true;
                    }
                }
                return false;
            case $matches === "none":
            case $matches === 0:
                return array_sum($counts) == 0;
            default:
                if ($assoc) {
                    $matchCount = 0;
                    foreach ($counts as $word => $occurrences) {
                        if ($occurrences >= $words[$word]) {
                            if (++$matchCount >= $matches) {
                                return true;
                            }
                        }
                    }
                    return false;
                }
                $counts = array_filter($counts);
                return count($counts) >= $matches;
        }
    }

    /**
     * Node has special sentence patterns.
     *
     * @see sentences()
     * @see questions()
     * @see exclamatory()
     *
     * @args delimiters
     *     Default: "!?."
     *     string|array ~ characters used to delimit sentences.
     * @args marks
     *     Default: "!?."
     *     string|array ~ punctuation for sentences we'll consider.
     * @args type
     *     Required.
     * @args min_words
     *     Default: 1
     *     int ~ min words make a valid sentence?
     * @args case_sensitive
     *     Default: false
     * @args regex
     *     Default: false
     *     string ~ regular expression our sentence structures should match.
     * @args children
     *     Default: false
     *     Consider text in children nodes.
     * @args position
     *     Default: 0
     *     Scrapers should auto include this param.
     * @args matches
     *     Default: any
     *     Require all, any, none, or a numeric amount of matches.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function punctuation(AiCrawler &$node, $args = [])
    {
        $type = self::arg($args, 'type');

        // Fallback to punctuation settings.
        $delimiters = self::arg($args, 'delimiters');
        $marks = self::arr($args, 'marks', '');

        // Fallback to $type settings
        $search = self::arg($args, $type, $type);
        $min_words = self::arg($args, 'min_words', $type);
        $regex = self::boolean($args, 'regex', $type);
        $case_sensitive = self::boolean($args, 'case_sensitive', $type);
        $position = self::arg($args, 'position', $type);
        $children = self::boolean($args, 'children', $type);
        $matches = self::arg($args, 'matches', $type);

        $text = self::text($node, $position, $children, $case_sensitive);

        /**
         * There is no text, we can figure this one out quickly.
         */
        if ($text == "") {
            return $matches === 0 || $matches == 'none';
        }

        /**
         * Gather sentences.
         */
        $sentences = preg_split('/(?<=['.$delimiters.'])./', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        /**
         * Filter out sentences that don't have the minimum amount of words.
         */
        if ($min_words > 1) {
            $sentences = array_filter($sentences, function($s) use ($min_words) {
                return str_word_count($s) >= $min_words;
            });
        }

        /**
         * Filter out sentences that don't end in one of the marks.
         */
        $sentences = array_filter($sentences, function($sentence) use ($sentences, $marks) {
            $last = substr($sentence, -1);
            return in_array($last, $marks);
        });

        /**
         * Filter out sentences that don't match a string.
         */
        $countBeforeSearch = count($sentences);
        if (is_string($search)) {
            if ($regex) {
                $sentences = array_filter($sentences, function($sentence) use ($search) {
                    return preg_match($search, $sentence);
                });
            } else {
                $sentences = array_filter($sentences, function($sentence) use ($search) {
                    return strpos($sentence, $search) !== false;
                });
            }
        }
        $countAfterSearch = count($sentences);

        /**
         * Handle matches
         */
        switch (true) {
            case $matches === "all":
                return $countBeforeSearch == $countAfterSearch;
            case $matches === "any":
                return $countAfterSearch > 0;
            case $matches === "none":
            case $matches === 0:
                return $countAfterSearch == 0;
            default:
                return $countAfterSearch >= $matches;
        }
    }

    /**
     * Find sentences.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function sentences(AiCrawler &$node, $args = [])
    {
        $args['type'] = __FUNCTION__;
        return self::punctuation($node, $args);
    }

    /**
     * Find questions.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function questions(AiCrawler &$node, $args = [])
    {
        $args['type'] = __FUNCTION__;
        $args['marks'] = '?';
        return self::punctuation($node, $args);
    }

    /**
     * Find exclamatory sentences.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function exclamatory(AiCrawler &$node, $args = [])
    {
        $args['type'] = __FUNCTION__;
        $args['marks'] = '!';
        return self::punctuation($node, $args);
    }

    /**
     * Node is a paragraph element
     *
     * @args Unused. All rules should have common interface / signature.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function p(AiCrawler &$node, array $args = [])
    {
        return (strtolower($node->nodeName()) == 'p');
    }

    /**
     * Node is an anchor element with an optionally specified domain.
     *
     * @args domain
     *     Default: false
     *     string ~ anchor must have domain.
     * @args regex
     *     Default: false.
     *     Domain specified uses regular expression.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function a(AiCrawler &$node, array $args = [])
    {
        $domain = self::arg($args, 'domain');
        $regex = self::arg($args, 'regex');

        if ($node->nodeName() == 'a') {
            if ($domain) {
                $href = $node->attr('href');
                if (! empty($href)) {
                    $domainHref = strtolower(RegEx::urlDomain($href));
                    return $regex
                        ? preg_match($domain, $domainHref)
                        : strpos($domainHref, strtolower($domain)) !== false;
                }
                return false;
            }
            return true;
        }
        return false;
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
    public function img(AiCrawler &$node, array $args)
    {
        // todo: make an image Heuristic
        return true;
    }

    /**
     * Node is an element of types supplied.
     *
     * @args elements
     *     Required.
     *     string ~ single element or multiple elements separated by space.
     *     array ~ multiple elements
     * @args regex
     *     Default: false.
     *     Elements specified use regular expression.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function elements(AiCrawler &$node, array $args = [])
    {
        $elements = self::arr($args, 'elements', ' ');
        $regex = self::arg($args, 'regex');

        $nodeElement = strtolower($node->nodeName());

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
     * Node has attribute(s) of a type in a list.
     *
     * @args attributes
     *     Default: see self::$attributes properties
     *     string ~ single attribute or multiple attributes separated by space.
     *     array ~ multiple attributes
     * @args matches
     *     Default: any
     *     Require all, any, none, or a numeric amount of matches.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function attributes(AiCrawler &$node, array $args = [])
    {
        $attributes = self::arr($args, 'attributes');
        $matches = self::arg($args, 'matches');

        $attributesFound = [];
        foreach ($attributes as $a) {
            $attributesFound[$a] = $node->attr($a);
        }
        $attributesFound = array_diff($attributesFound, [null]);

        switch (true) {
            case $matches === "all":
                return count($attributes) == count($attributesFound);
            case $matches === "any":
                return boolval(count($attributesFound));
            case $matches === "none":
            case $matches === 0:
                return ! boolval(count($attributesFound));
            default:
                return count($attributesFound) >= (int) $matches;
        }
    }

    /**
     * Node has attribute(s) with value(s), matching special criteria.
     *
     * @args values
     *     Required
     *     string ~ single value or multiple values separated by space.
     *     array ~ values that any of the attributes may match.
     *     array ~ assoc with keys: attribute and values: values (string or
     *             array) in which the respective attribute should match.
     * @args attributes
     *     Default: see self::$attributes_values properties.
     *     string ~ single attribute or multiple attributes separated by space.
     *     array ~ multiple attributes
     * @args case_sensitive
     *     Default: false
     * @args regex
     *     Default: false
     *     Attribute values contain regular expressions.
     * @args matches
     *     Default: any
     *     Require all, any, none, or a numeric amount of matches.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function attribute_values(AiCrawler &$node, array $args)
    {
        $values = self::arr($args, 'values', ' ');
        $case_sensitive = self::boolean($args, 'case_sensitive');
        $regex = self::boolean($args, 'regex');
        $matches = self::arg($args, 'matches');

        $assoc = ! isset($values[0]);
        /**
         * If values is associative array, use keys for attributes.
         */
        $attributes = $assoc
            ? array_keys($values) : self::arr($args, 'attributes', ' ');

        $attributesFound = [];
        foreach ($attributes as $a) {
            $attributesFound[$a] = $case_sensitive
                ? $node->attr($a) : strtolower($node->attr($a));
        }
        $attributesFound = array_filter($attributesFound);

        /**
         * Each attribute must match its associated value.
         */
        $hits = 0;
        if ($assoc) {
            foreach($values as $attr => $value) {
                if (isset($attributesFound[$attr])) {
                    if ($regex && preg_match($value, $attributesFound[$attr])) {
                        $hits ++;
                    } elseif($value == $attributesFound[$attr]) {
                        $hits ++;
                    }
                }
            }
        /**
         * Each attribute may carry any of the values we provided.
         */
        } else {
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
        }

        /**
         * Handle matches.
         */
        switch ($matches) {
            case "all":
                return ! $assoc
                    ? $hits == count($attributes)
                    : $hits == count($values);
            case "any":
                return boolval($hits);
            case "none":
                return ! boolval($hits);
            default:
                return $hits >= (int) $matches;
        }
    }

    /**
     * Node has previously scored on previous rules.
     *
     * @args item
     *     Required
     *     The data point on the node in which the assertion is being made.
     * @args rules
     *     Required
     *     The rules that the data point must have hit for our assertion.
     * @args matches
     *     Default: any
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function after_hitting(AiCrawler &$node, array $args)
    {
        $item = self::arg($args, 'item');
        $rules = self::arr($args, 'rules', ' ');
        $matches = self::arg($args, 'matches');

        $hits = [];
        foreach ($rules as $rule) {
            if ($node->hasDataPoint($item, $rule) &&
                $node->dataPoint($item, $rule) > 0)
            {
                $hits++;
            }
        }

        /**
         * Handle matches
         */
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
     * @args item
     *     Required
     *     The data point on the node in which the assertion is being made.
     * @args rules
     *     Required
     *     The rules that the data point must have missed for our assertion.
     * @args matches
     *     Default: any
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function after_missing(AiCrawler &$node, array $args)
    {
        // Rules and item are required fields, exception thrown if omitted.
        $item = self::arg($args, 'item');
        $rules = self::arr($args, 'rules');
        $matches = self::boolean($args, 'matches');

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
     * Run multiple heuristics on a subset relation of the node.
     *
     * A subset method must be available on your Crawler class. The Symfony
     * DOMCrawler gives us children(), siblings(), and parents(). Unique
     * subsets? Extend AiCrawler.
     *
     * This allows us to build a complex assertion about a node based on
     * a multitude of assertions on related nodes.
     *
     * To build a complex assertion, we need the option for complex matching.
     * With on(), we may interpret matches in a couple of new ways. I recommend
     * reviewing the "Handle matches" doc below.
     *
     * @args *
     *     Mixed
     *     Provide any of the rules (in self::$subsetFunctions) and args for
     *     each respective rule. If you want to run the same rule more than
     *     once with different args, append 1, 2, n...
     * @args matches
     *     Default: any
     *     int ~ number of rules hit with respect to their matches param.
     *     string ~ think of the hyphenated options as <nodes>-<rules>: all,
     *         all-any, all-none, any, any-all, any-none none
     *     array ~ rules that must pass on at least one of the nodes.
     *     assoc array ~ alternatively specify matches for your rules in an
     *         assoc array. Note: omitting matches from each rule uses default.
     *
     * $args example:
     *
     * For the parent assertion to hit, 3 children must be p, blockquote, or
     * heading elements and have 5 or more words.
     *
     * [
     *     'words' => [
     *         'matches' => '5'
     *     ],
     *     'elements' => [
     *         'elements' => 'p blockquote'
     *     ],
     *     'elements2' => [
     *         'elements' => '/h[1-6]/',
     *         'regex' => true,
     *         'matches' => 'none'
     *     ],
     *     'matches' => 3
     * ]
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     *
     * @see self::$subsetFunctions
     * @todo majority? handle <50%>-<80%> 50+% of the nodes, hit 80+% of rules
     */
    public static function on(AiCrawler &$node, array $args = [])
    {
        $on = self::arg($args, 'on');
        try {
            $matches = self::arg($args, 'matches', $on);
        } catch (InvalidArgumentException $e) {
            $matches = self::arg($args, 'matches');
        }

        /**
         * Our Crawler must have a method to get a subset. e.g. children().
         */
        if (! method_exists($node, $on)) {
            throw new InvalidArgumentException(
                "{$on} is not a valid method on your crawler."
            );
        }

        $heuristics = array_intersect_key($args, self::$subsetFunctions);

        /**
         * No rules were given, assert if $on is empty.
         */
        if (empty($heuristics)) {
            return $matches == 'none' || $matches === 0
                ? ! boolval($node->$on()->count())
                : boolval($node->$on()->count());
        }

        /**
         * Check for valid rules and rename rules that have number appended.
         */
        foreach ($heuristics as $rule) {
            $rule = preg_replace('/[0-9]+/', '', $rule);
            if (! method_exists(self, $rule)) {
                throw new InvalidArgumentException(
                    "Heuristic: {$rule} is not a valid subset function. Use ".
                    implode(", ", self::$subsetFunctions)."."
                );
            }
        }

        $size = $node->$on()->count();

        /**
         * Run all the rules on each node in the subset.
         */
        $ruleHits = [];
        foreach ($heuristics as $rule => $args) {
            $method = preg_replace('/[0-9]+/', '', $rule);
            $node->$on()->each(function($n) use (&$ruleHits, $rule, $method, $args) {
                if (self::$method($n, $args)) {
                    $ruleHits[$rule]++;
                }
            });
        }

        /**
         * Handle matches.
         */
        switch ($matches) {
            /**
             * Did all nodes pass all the rules??
             */
            case "all":
                if (count($ruleHits) == count($heuristics)) {
                    foreach($ruleHits as $rule => $hits) {
                        if ($hits != $size) {
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
                return count($heuristics) == count($ruleHits);

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
                    if ($hits == $size) {
                        return true;
                    }
                }
                return false;

            /**
             * Did any of the nodes pass none of the rules?
             */
            case "any-none":
                return count($ruleHits) < count($heuristics);

            /**
             * Oh snap, you just changed the game!
             */
            default:
                $matches = is_string($matches)
                    ? explode(" ", $matches) : $matches;

                if (is_array($matches)) {
                    /**
                     * Some or all node(s) collective hit all rules specified
                     * with respect to their own matches argument.
                     *
                     * e.g.
                     * ['p', 'words', 'words2' ...] or "p words words2 ..."
                     */
                    if (isset($matches[0])) {
                        $ruleHitsKeys = array_keys($ruleHits);
                        $matchesRHK = array_intersect($ruleHitsKeys, $matches);
                        return count($matchesRHK) == count($matches);
                    /**
                     * Some or all node(s) hit rules in specified way.
                     *
                     * e.g.
                     * [
                     *     'p' => all|any|none|#
                     *     'words' => all|any|none|#
                     *     'words2' => all|any|none|#
                     *     ...
                     * ]
                     */
                    } else {
                        foreach($matches as $rule => $ruleMatches) {
                            switch(true) {
                                case $ruleMatches === "all":
                                    if ($ruleHits[$rule] < $size) {
                                        return false;
                                    }
                                    break;
                                case $ruleMatches === "any":
                                    if (! isset($ruleHits[$rule])) {
                                        return false;
                                    }
                                case $ruleMatches === 0:
                                case $ruleMatches === "none":
                                    if (isset($ruleHits[$rule])) {
                                        return false;
                                    }
                                default:
                                    if (! isset($ruleHits[$rule]) ||
                                        $ruleHits[$rule] < $ruleMatches) {
                                        return false;
                                    }
                            }
                        }
                        return true;
                    }
                }

                /**
                 * Number of rules hit with respect to their matches argument.
                 */
                return count($ruleHits) >= (int) $matches;
        }
    }

    /**
     * Node has children which match on subset functions provided.
     *
     * @see self::on()
     *
     * @param AiCrawler $node
     * @param array $args matches, subsetFunctions
     *
     * @return bool
     */
    public static function children(AiCrawler &$node, array $args = [])
    {
        $args['on'] = __FUNCTION__;
        return self::on($node, $args);
    }

    /**
     * Node has siblings which match on subset functions provided.
     *
     * @see self::on()
     *
     * @param AiCrawler $node
     * @param array $args matches, subsetFunctions
     *
     * @return bool
     */
    public static function siblings(AiCrawler &$node, array $args = [])
    {
        $args['on'] = __FUNCTION__;
        return self::on($node, $args);
    }

    /**
     * Node has parents which match on subset functions provided.
     *
     * @see self::on()
     *
     * @param AiCrawler $node
     * @param array $args matches, subsetFunctions
     *
     * @return bool
     */
    public static function parents(AiCrawler &$node, array $args = [])
    {
        $args['on'] = __FUNCTION__;
        return self::on($node, $args);
    }

    /**
     * Get the key or return the default.
     *
     * @param $args
     * @param $key
     * @param null $function
     *
     * @return mixed
     */
    protected function arg($args, $key, $function = null)
    {
        if (isset($args[$key])) {
            return $args[$key];
        }

        $function = $function ?: debug_backtrace()[1]['function'];

        if (isset(static::${$function}[$key])) {
            return static::${$function}[$key];
        } elseif (isset(static::$defaults[$key])) {
            return static::$defaults[$key];
        } else {
            throw new InvalidArgumentException(
                __CLASS__."::\${$function} property with key {$key} could not be found."
            );
        }
    }

    /**
     * Get an array argument.
     *
     * @param $args
     * @param $key
     *
     * @param bool $explodeDelimiter
     * @return bool
     */
    protected function arr($args, $key, $explodeDelimiter = false)
    {
        $function = debug_backtrace()[1]['function'];
        $arg = self::arg($args, $key, $function);
        if ($explodeDelimiter !== false && is_string($arg)) {
            if ($explodeDelimiter == '') {
                return str_split($arg);
            }
            return explode($explodeDelimiter, $arg);
        }
        return (array) $arg;
    }

    /**
     * Sugar so the dev knows it should be boolean.
     *
     * @param $args
     * @param $key
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function boolean($args, $key)
    {
        $function = debug_backtrace()[1]['function'];
        return self::arg($args, $key, $function);
    }

    /**
     * Get the text for a node.
     *
     * @param AiCrawler $node
     * @param int $position
     * @param bool $children
     * @param bool $case_sensitive
     * @param bool $ascii
     *
     * @return string
     */
    protected function text(AiCrawler &$node, $position, $children, $case_sensitive, $ascii = false)
    {
        if ($children) {
            $text = RegEx::removeExtraneousWhitespace($node->text());
        } else {
            $copy = $node->createChildlessSubCrawler($position);
            $text = $copy ? RegEx::removeExtraneousWhitespace($copy->text()) : "";
        }
        if ($ascii) {
            $text = RegEx::ascii($text);
        }
        return $case_sensitive ? $text : strtolower($text);
    }

}