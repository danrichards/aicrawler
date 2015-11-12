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
 * Advice when extending Heuristics:
 *
 * 1. Method names should never intersect with argument names.
 * 2. Method names should not have integers in them.
 * 3. Usage of arg, arr, boolean and text helper methods is encouraged.
 * 4. Method should return static::subset(...) on true when possible.
 * 5. Subset methods (e.g. children()) should not return static::subset(...).
 * 6. Heuristics methods should follow the following interface:
 *    `public static function heuristic(AiCrawler &$node, array $args = [])`
 * 7. Heuristics methods should be snake_case (sorry).
 *
 * @todo first() method ~ parent node's first [child, parent, sibling]
 * @todo last() method ~ parent node's last [child, parent, sibling]
 * @todo nth_child method ~ parent node's n-th [child, parent, sibling]
 *
 * @todo $matches === 0 requires exactly 0 to be true
 * @todo @matches === '0' requires 0 or more to be true
 */
class Heuristics
{
    /**
     * Methods which can run subsequent methods upon asserting true.
     *
     * @var array $subsetFunctions
     */
    public static $subsetFunctions = [
        'characters' => 'characters',
        'words' => 'words',
        'sentences' => 'sentences',
        'p' => 'p',
        'a' => 'a',
        'elements' => 'elements',
        'attributes' => 'attributes',
        'attribute_values' => 'attribute_values',
        'on' => 'on',
        'children' => 'children',
        'siblings' => 'siblings',
        'parents' => 'parents',
        'after_hitting' => 'after_hitting',
        'after_missing' => 'after_missing'
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
        'descendants' => false,
        'regex' => false,
    ];

    /**
     * Defaults for characters()
     *
     * @var array $characters
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
     * @args descendants
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
    public static function characters(AiCrawler &$node, array $args = [])
    {
        $characters = static::arg($args, 'characters');
        $case_sensitive = static::arg($args, 'case_sensitive');
        $descendants = static::boolean($args, 'descendants');
        $position = static::arg($args, 'position');
        $matches = static::arg($args, 'matches');

        $text = static::text($node, $position, $descendants, $case_sensitive, true);

        /**
         * There is no text, we can figure this one out quickly.
         */
        if ($text == "") {
            return static::subset($node, $args,
                ($matches === 'none' || $matches === 0));
        /**
         * Just count the characters.
         */
        } elseif ($characters === true || is_int($characters)) {
            return static::subset($node, $args,
                strlen($text) > (int) $characters);
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
                return static::subset($node, $args);
            case $matches === "any":
                foreach ($ascii as $ord => $occurrences) {
                    if (isset($counts[$ord]) && $counts[$ord] >= $ascii[$ord]) {
                        return static::subset($node, $args);
                    }
                }
                return false;
            case $matches === "none":
            case $matches === 0:
                return static::subset($node, $args,
                    count(array_intersect_key($ascii, $counts)) == 0);
            default:
                if ($assoc) {
                    $matchCount = 0;
                    foreach ($ascii as $ord => $occurrences) {
                        if (isset($counts[$ord]) && $counts[$ord] >= $ascii[$ord]) {
                            if (++$matchCount >= $matches) {
                                return static::subset($node, $args);
                            }
                        }
                    }
                    return false;
                }
                return static::subset($node, $args,
                    count(array_intersect_key($ascii, $counts)) >= $matches);
        }
    }

    /**
     * Node has special word patterns.
     *
     * @args words
     *     Default: true
     *     bool(true) ~ any character match.
     *     int ~ num words is at least. The 'matches' param is ignored.
     *     string ~ a string to match
     *     array ~ numerically indexed array of words.
     *     assoc array ~ keys: words, values: min occurrences required.
     * @args case_sensitive
     *     Default: false
     *     When true, words given assumed lowercase.
     * @args regex
     *     Default: false
     *     When true, words string or arrays use regular expression.
     * @args descendants
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
     * @param array $args
     *
     * @return bool
     *
     */
    public static function words(AiCrawler &$node, array $args)
    {
        $words = static::arg($args, 'words');
        $case_sensitive = static::boolean($args, 'case_sensitive');
        $regex = static::boolean($args, 'regex');
        $descendants = static::boolean($args, 'descendants');
        $position = static::arg($args, 'position');
        $matches = static::arg($args, 'matches');

        $text = static::text($node, $position, $descendants, $case_sensitive);

        /**
         * There is no text, we can figure this one out quickly.
         */
        if ($text == "") {
            return static::subset($node, $args,
                $matches === 'none' || $matches === 0);
            /**
             * Just count the words.
             */
        } elseif ($words === true || is_int($words)) {
            return static::subset($node, $args,
                str_word_count($text) >= (int) $words);
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
                    return static::subset($node, $args,
                        array_search(0, $counts, true) === false);
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
                        return static::subset($node, $args);
                    }
                }
                return false;
            case $matches === "none":
            case $matches === 0:
                return static::subset($node, $args, array_sum($counts) == 0);
            default:
                if ($assoc) {
                    $matchCount = 0;
                    foreach ($counts as $word => $occurrences) {
                        if ($occurrences >= $words[$word]) {
                            if (++$matchCount >= $matches) {
                                return static::subset($node, $args);
                            }
                        }
                    }
                    return false;
                }
                $counts = array_filter($counts);
                return static::subset($node, $args,
                    count($counts) >= $matches);
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
     * @args descendants
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
    public static function punctuation(AiCrawler &$node, array $args = [])
    {
        $type = static::arg($args, 'type');

        // Fallback to punctuation settings.
        $delimiters = static::arg($args, 'delimiters');
        $marks = static::arr($args, 'marks', '');

        // Fallback to $type settings
        $search = static::arg($args, $type, $type);
        $min_words = static::arg($args, 'min_words', $type);
        $regex = static::boolean($args, 'regex', $type);
        $case_sensitive = static::boolean($args, 'case_sensitive', $type);
        $position = static::arg($args, 'position', $type);
        $descendants = static::boolean($args, 'descendants', $type);
        $matches = static::arg($args, 'matches', $type);

        $text = static::text($node, $position, $descendants, $case_sensitive);

        /**
         * There is no text, we can figure this one out quickly.
         */
        if ($text == "") {
            return static::subset($node, $args,
                $matches === 0 || $matches == 'none');
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
                return static::subset($node, $args,
                    $countBeforeSearch == $countAfterSearch, $type);
            case $matches === "any":
                return static::subset($node, $args,
                    $countAfterSearch > 0, $type);
            case $matches === "none":
            case $matches === 0:
                return static::subset($node, $args,
                    $countAfterSearch == 0, $type);
            default:
                return static::subset($node, $args,
                    $countAfterSearch >= $matches, $type);
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
    public static function sentences(AiCrawler &$node, array $args = [])
    {
        $args['type'] = __FUNCTION__;
        return static::punctuation($node, $args);
    }

    /**
     * Find questions.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function questions(AiCrawler &$node, array $args = [])
    {
        $args['type'] = __FUNCTION__;
        $args['marks'] = '?';
        return static::punctuation($node, $args);
    }

    /**
     * Find exclamatory sentences.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function exclamatory(AiCrawler &$node, array $args = [])
    {
        $args['type'] = __FUNCTION__;
        $args['marks'] = '!';
        return static::punctuation($node, $args);
    }

    /**
     * Node is a paragraph element
     *
     * @args Unused. All rules should have common interface / signature.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @todo add text and regex args
     *
     * @return bool
     */
    public static function p(AiCrawler &$node, array $args = [])
    {
        return static::subset($node, $args,
            strtolower($node->nodeName()) == 'p');
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
     * @todo add text and regex args
     *
     * @return bool
     */
    public static function a(AiCrawler &$node, array $args = [])
    {
        $domain = static::arg($args, 'domain');
        $regex = static::arg($args, 'regex');

        if ($node->nodeName() == 'a') {
            if ($domain) {
                $href = $node->attr('href');
                if (! empty($href)) {
                    $domainHref = strtolower(RegEx::urlDomain($href));
                    if ($regex) {
                        return static::subset($node, $args, preg_match($domain, $domainHref));
                    }
                    return static::subset($node, $args,
                        strpos($domainHref, strtolower($domain)) !== false);
                }
                return false;
            }
            return static::subset($node, $args);
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
     * @todo add text and regex args
     *
     * @return bool
     */
    public static function elements(AiCrawler &$node, array $args = [])
    {
        $elements = static::arr($args, 'elements', ' ');
        $regex = static::arg($args, 'regex');

        $nodeElement = strtolower($node->nodeName());

        if ($regex) {
            foreach($elements as $element) {
                if (preg_match($element, $nodeElement)) {
                    return static::subset($node, $args);
                }
            }
            return false;
        } else {
            return static::subset($node, $args,
                in_array($nodeElement, $elements));
        }
    }

    /**
     * Node has attribute(s) of a type in a list.
     *
     * @args attributes
     *     Default: see static::$attributes properties
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
        $attributes = static::arr($args, 'attributes');
        $matches = static::arg($args, 'matches');

        $attributesFound = [];
        foreach ($attributes as $a) {
            $attributesFound[$a] = $node->attr($a);
        }
        $attributesFound = array_diff($attributesFound, [null]);

        switch (true) {
            case $matches === "all":
                return static::subset($node, $args,
                    count($attributes) == count($attributesFound));
            case $matches === "any":
                return static::subset($node, $args,
                    boolval(count($attributesFound)));
            case $matches === "none":
            case $matches === 0:
                return static::subset($node, $args,
                    ! boolval(count($attributesFound)));
            default:
                return static::subset($node, $args,
                    count($attributesFound) >= (int) $matches);
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
     *     Default: see static::$attributes_values properties.
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
        $values = static::arr($args, 'values', ' ');
        $case_sensitive = static::boolean($args, 'case_sensitive');
        $regex = static::boolean($args, 'regex');
        $matches = static::arg($args, 'matches');

        $assoc = ! isset($values[0]);
        /**
         * If values is associative array, use keys for attributes.
         */
        $attributes = $assoc
            ? array_keys($values) : static::arr($args, 'attributes', ' ');

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
                if (! $assoc) {
                    return static::subset($node, $args, $hits == count($attributes));
                }
                return static::subset($node, $args, $hits == count($values));
            case "any":
                return static::subset($node, $args, boolval($hits));
            case "none":
                return static::subset($node, $args, ! boolval($hits));
            default:
                return static::subset($node, $args, $hits >= (int) $matches);
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
        $item = static::arg($args, 'item');
        $rules = static::arr($args, 'rules', ' ');
        $matches = static::arg($args, 'matches');

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
                return static::subset($node, $args, $hits == count($rules));
            case "any":
                return static::subset($node, $args, boolval($hits));
            case "none":
                return static::subset($node, $args, ! boolval($hits));
            default:
                return static::subset($node, $args, $hits >= (int) $matches);
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
        $item = static::arg($args, 'item');
        $rules = static::arr($args, 'rules');
        $matches = static::boolean($args, 'matches');

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
                return static::subset($node, $args, $misses == count($rules));
            case "any":
                return static::subset($node, $args, boolval($misses));
            case "none":
                return static::subset($node, $args, ! boolval($misses));
            default:
                return static::subset($node, $args, $misses >= (int) $matches);
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
     *     Provide any of the rules (in static::$subsetFunctions) and args for
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
     * @see static::$subsetFunctions
     * @todo majority? handle <50%>-<80%> 50+% of the nodes, hit 80+% of rules
     */
    public static function on(AiCrawler &$node, array $args = [])
    {
        $on = static::arg($args, 'on');

        /**
         * You may optionally specify a num matches with matching arg.
         */
        try {
            $methodParam = static::arg($args, $on);
        } catch (InvalidArgumentException $e) {
            $methodParam = null;
        }

        /**
         * Arg synonymous to method name with integer overrides matches.
         */
        if (! is_null($methodParam) && is_int($methodParam)) {
            $matches = $methodParam;
        } else {
            try {
                $matches = static::arg($args, 'matches', $on);
            } catch (InvalidArgumentException $e) {
                $matches = static::arg($args, 'matches');
            }
        }

        /**
         * Our Crawler must have a method to get a subset. e.g. children().
         */
        if (! method_exists($node, $on)) {
            throw new InvalidArgumentException(
                "{$on} is not a valid method on your crawler."
            );
        }

        $heuristics = array_diff_key($args, ['on' => null, $args['on'] => null]);
        $heuristics = array_intersect_key($heuristics, static::$subsetFunctions);

        /**
         * Check for valid rules and rename rules that have number appended.
         */
        foreach ($heuristics as $rule => $ruleArgs) {
            $rule = preg_replace('/[0-9]+/', '', $rule);
            if (! method_exists(__CLASS__, $rule)) {
                throw new InvalidArgumentException(
                    "Heuristic: {$rule} is not a valid subset function. Use ".
                    implode(", ", static::$subsetFunctions)."."
                );
            }
        }

        $size = $node->$on()->count();

        /**
         * Run all the rules on each node in the subset.
         */
        $ruleHits = [];
        $nodeHits = array_fill(0, $size, 0);
        foreach ($heuristics as $rule => $args) {
            $method = preg_replace('/[0-9]+/', '', $rule);
            $node->$on()->each(function($n, $i) use (&$ruleHits, &$nodeHits, $rule, $method, $args) {
                if (static::$method($n, $args)) {
                    if (isset($ruleHits[$rule])) {
                        $ruleHits[$rule]++;
                    } else {
                        $ruleHits[$rule] = 1;
                    }
                    $nodeHits[$i]++;
                }
            });
        }

        /**
         * Handle matches (no rules were given).
         */
        if (empty($heuristics)) {
            switch(true) {
                case $matches == 'none':
                case $matches === 0:
                    return $size == 0;
                default:
                    if (is_int($matches)) {
                        return $size >= $matches;
                    }
                    return boolval($size);
            }
        }

        /**
         * Handle matches.
         */
        switch (true) {
            /**
             * If rules are defined check $nodeHits, otherwise $size.
             */
            case $matches === 0:
                return ! boolval(count(array_diff($nodeHits, [0])));

            /**
             * Did all nodes pass all the rules??
             */
            case $matches == "all":
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
             * Did all nodes hit at least one rule?
             */
            case $matches == "all-any":
                return array_search(0, $nodeHits) === false;

            /**
             * Did all nodes hit none of the rules?
             */
            case $matches == "all-none":
            case $matches == "none":
                return empty($ruleHits);

            /**
             * Did any of the nodes hit any of the rules?
             */
            case $matches == "any":
                return ! empty($ruleHits);

            /**
             * Did any of the nodes hit all of the rules?
             */
            case $matches == "any-all":
                return array_search(count($heuristics), $nodeHits) !==  false;

            /**
             * Did any nodes hit none of the rules?
             */
            case $matches == "any-none":
                return array_search(0, $nodeHits) !== false;

            /**
             * Oh snap, you just changed the game!
             */
            default:
                $matches = is_string($matches)
                    ? explode(" ", $matches) : $matches;

                if (is_array($matches)) {

                    /**
                     * Did only these exact rules hit?
                     *
                     * e.g.
                     * ['p', 'words', 'words2' ...] or "p words words2 ..."
                     */
                    if (isset($matches[0])) {
                        $rules = array_keys($ruleHits);
                        $matchesRHK = array_intersect($rules, $matches);
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
                 * Did at least n children get hit by said rules?
                 */
                if (count($heuristics)) {
                    $nodesThatHit = count(array_diff($nodeHits, [0]));
                    return $matches === 0
                        ? $nodesThatHit == 0
                        : $nodesThatHit >= (int) $matches;
                }
                /**
                 * No rules, do we have at least n children?
                 */
                return $size >= (int) $matches;
        }
    }

    /**
     * Node has children which match on subset functions provided.
     *
     * @see static::on()
     *
     * @param AiCrawler $node
     * @param array $args matches, subsetFunctions
     *
     * @return bool
     */
    public static function children(AiCrawler &$node, array $args = [])
    {
        $args['on'] = __FUNCTION__;
        return static::on($node, $args);
    }

    /**
     * Node has siblings which match on subset functions provided.
     *
     * @see static::on()
     *
     * @param AiCrawler $node
     * @param array $args matches, subsetFunctions
     *
     * @return bool
     */
    public static function siblings(AiCrawler &$node, array $args = [])
    {
        $args['on'] = __FUNCTION__;
        return static::on($node, $args);
    }

    /**
     * Node has parents which match on subset functions provided.
     *
     * @see static::on()
     *
     * @param AiCrawler $node
     * @param array $args matches, subsetFunctions
     *
     * @return bool
     */
    public static function parents(AiCrawler &$node, array $args = [])
    {
        $args['on'] = __FUNCTION__;
        return static::on($node, $args);
    }

    /**
     * Get the key or return the default.
     *
     * @param $args
     * @param $key
     * @param null $function
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function arg(array $args, $key, $function = null)
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
     * @param bool $explodeDelimiter
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function arr(array $args, $key, $explodeDelimiter = false)
    {
        $function = debug_backtrace()[1]['function'];
        $arg = static::arg($args, $key, $function);
        if ($explodeDelimiter !== false && is_string($arg)) {
            if ($explodeDelimiter === '') {
                return str_split($arg);
            }
            return explode($explodeDelimiter, $arg);
        }
        return (array) $arg;
    }

    /**
     * Sugar so the dev knows the arg should be boolean.
     *
     * @param $args
     * @param $key
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function boolean(array $args, $key)
    {
        $function = debug_backtrace()[1]['function'];
        return static::arg($args, $key, $function);
    }

    /**
     * Get the text for a node.
     *
     * @param AiCrawler $node
     * @param int $position
     * @param bool $descendants
     * @param bool $case_sensitive
     * @param bool $ascii
     *
     * @return string
     */
    protected function text(AiCrawler &$node, $position = 0, $descendants = false, $case_sensitive = false, $ascii = false)
    {
        if ($descendants) {
            $text = RegEx::removeExtraneousWhitespace($node->text());
        } else {
            $copy = $node->createChildlessSubCrawler($position) ?: "";
            $text = RegEx::removeExtraneousWhitespace($copy->text());
        }
        if ($ascii) {
            $text = RegEx::ascii($text);
        }
        return $case_sensitive ? $text : strtolower($text);
    }

    /**
     * On the condition passing, array_intersect with static::$subsetFunctions
     * and run all those methods on the node provided. This enables us to nest
     * our rules and continue down some rabbit hole to make an assertion.
     *
     * Nesting rules is like applying the AND operator. Neat!
     *
     * @param AiCrawler $node
     * @param array $args
     * @param bool $condition
     * @param null $function
     *
     * @return bool
     */
    protected function subset(AiCrawler &$node, array $args = [], $condition = true, $function = null)
    {
        if ($condition) {
            $function = $function ?: debug_backtrace()[1]['function'];
            /**
             * Don't call the calling function again!
             */
            $heuristics = array_diff_key($args, [$function => null]);
            /**
             * Only call functions in our subsetFunctions list!
             */
            $heuristics = array_intersect_key($heuristics, static::$subsetFunctions);

            foreach ($heuristics as $rule => $args) {
                if (! static::$rule($node, $args)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

}