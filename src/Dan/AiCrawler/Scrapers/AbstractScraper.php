<?php

namespace Dan\AiCrawler\Scrapers;

use Dan\AiCrawler\Exceptions\HeuristicClassNotFoundException;
use Dan\AiCrawler\Support\AiCrawler;
use Dan\Core\ViewSource\HtmlSourceDownloader;

/**
 * Class AbstractScraper
 *
 * @package Dan\AiCrawler\Scrapers
 * @author Dan Richards <danrichardsri@gmail.com>
 */
abstract class AbstractScraper {

    /**
     * runRules() will unset the data point if false (strict) is returned from
     * a heuristic.
     */
    const OMIT_DATA_POINT = false;

    /**
     * runRules() will unset the item (and all it's data points) if this string
     * is returned from a heuristic.
     */
    const OMIT_ITEM = 'omit_item';

    /**
     * Our AiCrawler object
     */
    protected $html;

    /**
     * The current item we're scoring
     *
     * @var $item
     */
    protected $item;

    /**
     * The current rule set for scoring
     *
     * @var $rules
     */
    protected $rules;

    /**
     * The actual rules (functions) that score our nodes
     *
     * @var string $heuristics
     */
    protected $heuristics;

    /**
     * Default Configuration
     *
     * @param AiCrawler|html|url $node
     * @param string $heuristics
     */
    public function __construct($node = null, $heuristics = 'Dan\\AiCrawler\\Heuristics')
    {
        $this->using($heuristics)->crawl($node);
    }

    /**
     * Score all your heuristics with BFS.
     *
     * @param $item
     * @param $rules
     *
     * @return $this
     */
    public function score($item, $rules)
    {
        $this->item = $item;
        $this->rules = $rules;
        self::bfs($this->html);
    }

    /**
     * Provide content to scraper, as html, a url, or an AiCrawler object.
     *
     * @param mixed $html
     *
     * @return $this
     * @throws SourceNotFoundException
     */
    public function crawl($html)
    {
        if ($html instanceof AiCrawler) {
            $this->html = $html;
        } else {
            $html = trim($html);
            if (strtolower(substr($html, 0, 4)) == "http") {
                $source = HtmlSourceDownloader::get($html);
                $html = $source->getSource();
            }
            $this->html = new AiCrawler($html);
        }
        return $this;
    }

    /**
     * Override the Heuristics provided to your AiCrawler with your own.
     *
     * @param $class
     *
     * @return $this
     * @throws HeuristicClassNotFoundException
     */
    public function using($class)
    {
        if (class_exists($class)) {
            $this->heuristics = $class;
        } else {
            throw new HeuristicClassNotFoundException($class, "When overriding Heuristics, make sure you specify the
                complete name-spaced path.");
        }
        return $this;
    }

    /**
     * Use Bread-First Search to score each node in the AiCrawler object
     *
     * @param AiCrawler $node
     */
    protected function bfs(AiCrawler &$node)
    {
        $this->runRules($node);
        /**
         * Score each node in the DOM
         */
        $node->children()->each(function ($n) {
            if ($n) {
                self::bfs($n);
            }
        });
    }

    /**
     * @return AiCrawler object
     */
    public function getCrawlerWithScores()
    {
        return $this->html;
    }

    /**
     * Abstracted from bfs() in case you want to run rules on a single node or
     * override the way rules are applied.
     *
     * @param AiCrawler $node
     *
     * @return $this
     * @throws MissingPointsAttributeException
     */
    public function runRules(AiCrawler &$node)
    {
        foreach ($this->rules as $rule => $args) {
            if (isset($args['points'])) {
                $operation = $args['points'];
            } else {
                throw new MissingPointsAttributeException($rule, "Be sure were you defined your {$rule} rule, you
                    provided a points attribute.");
            }
            $args['item'] = isset($args['item']) ? $args['item'] : $this->item;
            $result = call_user_func([$this->heuristics, $rule], $args);
            if ($result) {
                $score = $node->dataPoint($this->item, $rule);
                $newScore = $this->count($score, $operation);
                $this->distributeTo($node, $this->item, $rule, $newScore);
            } else {
                switch($operation) {
                    case self::OMIT_DATA_POINT:
                        $node->removeDataPoints($this->item, [$rule]);
                        break;
                    case self::OMIT_ITEM:
                        $node->removeItem($this->item);
                        break;
                }
            }
        }
        return $this;
    }

    /**
     * Allow some very simple arithmetic for our points distribution
     *
     * @param $value
     * @param $arithmetic
     *
     * @return float
     */
    public static function count($value, $arithmetic)
    {
        $operation = substr($arithmetic, 0, 1);
        switch($operation) {
            case "^":
                return pow($value, $arithmetic);
            case "-":
                return $value - substr($arithmetic, 1);
            case "*":
                return $value * substr($arithmetic, 1);
            case "/":
                $divisor = substr($arithmetic, 1);
                // don't divide by zero
                return $divisor != 0 ? $value / $divisor : 0;
            default:
                return $value + $arithmetic;
        }
    }

    /**
     * We got points, what are they for and who wants them? By default, they'll
     * goto the node given.
     *
     * @param $node
     * @param $item
     * @param $rule
     * @param $newScore
     * @param string $who
     *
     * @return $this
     */
    protected function distributeTo($node, $item, $rule, $newScore, $who = 'self')
    {
        switch($who) {
            case "children":
                break;
            case "siblings":
                break;
            case "parents":
                break;
            // self
            default:
                $node->dataPoint($item, $rule, $newScore);
        }
        return $this;
    }

//    protected function where()
//    {
//        if (is_callable($scores)) {
//            // call back to filter results
//        } else {
//            switch($qualifier) {
//                case ">":
//                    break;
//                case ">=":
//                    break;
//                case "=":
//                    break;
//                case "<":
//                    break;
//                case "<=":
//                    break;
//            }
//        }
//    }

    /**
     * A simple object our API will use. Calls the response() method on all your Heuristics.
     *
     * Note: this method only renders the top consideration (generally the first element, after sorting (see choose()).
     *
     * Example Response:
     *
     *    {
     *      "status": "200",
     *      "message": "",
     *      "data":
     *      [
     *          {
     *              "headline": {...flattened with $this->extra}
     *              "content": {...flattened with $this->extra}
     *              "image": {...flattened with $this->extra}
     *          }
     *      ]
     *   }
     */
//    public function response() {
//        $heuristics = $this->getHeuristics();
//        if (count($heuristics)) {
//            $response = new \stdClass();
//            $response->status = "200";
//            $response->message = "";
//
//            $dataObject = new \stdClass();
//            foreach ($heuristics as $context => $class) {
//                if ($this->payload[$context]->count()) {
//                    /**
//                     * Get response() the Heuristic
//                     */
//                    $contextObject = new \stdClass();
//                    if (class_exists($class)) {
//                        $contextObject = call_user_func_array(
//                            [$class, "response"],
//                            [$this->payload[$context]->first(), $context, $this->extra]
//                        );
//                    } elseif (class_exists("\\Dan\\AiCrawler\\Heuristics\\" . $class)) {
//                        $contextObject = call_user_func_array(
//                            ["\\Dan\\AiCrawler\\Heuristics\\" . $class, "response"],
//                            [$this->payload[$context]->first(), $context, $this->extra]
//                        );
//                    }
//                } else {
//                    $contextObject = null;
//                }
//                $dataObject->{$context} = $contextObject;
//            }
//            $response->data = [$dataObject];
//            return $response;
//        }
//        return null;
//    }

}