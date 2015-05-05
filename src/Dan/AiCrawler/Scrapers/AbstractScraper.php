<?php namespace Dan\AiCrawler\Scrapers;

use Dan\AiCrawler\Support\AiConfig;
use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Considerations;
use Dan\AiCrawler\Support\Exceptions\HeuristicConventionViolatedException;
use Dan\AiCrawler\Support\Exceptions\HeuristicDoesNotExistException;
use Dan\AiCrawler\Support\Exceptions\HeuristicsNotDefinedException;
use Dan\AiCrawler\Support\ExtraTrait;
use Dan\AiCrawler\Support\Source;

abstract class AbstractScraper {

    /**
     * AiConfig object
     *
     * @var $config Object
     */
    protected $config;

    /**
     * Our master html node
     */
    protected $html;

    /**
     * An associative array of Considerations collections.
     *
     * @var $payload array(Considerations)
     */
    protected $payload = [];

    /**
     * Our Scraper won't be operable until we've defined some heuristics to use.
     *
     * @var $heuristics
     */
    protected $heuristics = [];

    /**
     * Sanitizers are useful for tidying up our payload before we do something with it. Sanitizers will automatically
     * call themselves when the choose() method is invoked. But you still have the option to set them explicitly.
     *
     * @var $sanitizers
     */
    protected $sanitizers = [];

    /**
     * render() will convert this associative array to object properties
     *
     * @var $extra
     * @contains methods setExtra([$key|[assoc], $data) and getExtra($key|[$keys])
     */
    use ExtraTrait;

    /**
     * Default Configuration
     *
     * @param AiCrawler|html|url $node
     * @param array $config
     */
    function __construct($node = null, $config = []) {
        $this->config = new AiConfig($config);
        $this->setHtml($node);
        if (!is_null($this->html))
            $this->scrape();
    }

    /**
     * It may be advantageous to augment our $html node tree before running the scraper. This methods exists so we
     * can make accommodations for anomalies (ie. shitty html conventions).
     *
     * @see Any website on the AOL network :p
     *
     * @return $this
     */
    public function prep() {
        return $this;
    }

    /**
     * Bread-first search, running your heuristics and scoring the nodes. Considerations are gathered into the payload.
     *
     * @todo make a scrapeMany() method for a little speed boost. Score multiple (Considerations agnostic) heuristics in the same loop.
     *
     * @return $this
     */
    public function scrape(){
        if (count($heuristics = $this->getHeuristics())) {
            foreach($heuristics as $context => $class) {
                $this->payload[$context] = new Considerations();
                if (substr($class, -9) != "Heuristic")
                    throw new HeuristicConventionViolatedException("All heuristic class definitions must end in
                        Heuristic. Rename your class \"" . $class . "Heuristic\" or go home and cry to momma.");
                elseif (class_exists($class))
                    self::bfs(
                        $this->html,
                        $context,
                        call_user_func_array(array($class, "score"), array($this->html, $this->payload[$context]))
                    );
                elseif (class_exists("\\Dan\\AiCrawler\\Heuristics\\".$class))
                    self::bfs(
                        $this->html,
                        $context,
                        ["\\Dan\\AiCrawler\\Heuristics\\" . $class, "score"]
                    );
                else
                    throw new HeuristicDoesNotExistException("The $class heuristic you configured, does not exist.
                        Make sure it's in the \\Dan\\AiCrawler\\Heuristics\\ namespace. Otherwise, provide the
                        fully-qualified namespace path.");
            }
        } else {
            throw new HeuristicsNotDefinedException("You may not run scrape() until you've defined some Heuristics to
                use. Use AbstractScraper::setHeuristics()");
        }
        return $this;
    }

    /**
     * A simple object our API will use. Calls the render() method on all your Heuristics.
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
    public function render() {
        if (count($heuristics = $this->getHeuristics())) {
            $render = new \stdClass();
            $render->status = "200";
            $render->message = "";
            $data = [];

            $dataObject = new \stdClass();
            foreach ($heuristics as $context => $class) {
                if ($this->payload[$context]->count()) {
                    /**
                     * render() the Heuristic
                     */
                    $contextObject = new \stdClass();
                    if (class_exists($class))
                        $contextObject = call_user_func_array(
                            [$class, "render"],
                            [$this->payload[$context]->first(), $context, $this->extra]
                        );
                    elseif (class_exists("\\Dan\\AiCrawler\\Heuristics\\" . $class))
                        $contextObject = call_user_func_array(
                            ["\\Dan\\AiCrawler\\Heuristics\\" . $class, "render"],
                            [$this->payload[$context]->first(), $context, $this->extra]
                        );
                } else {
                    $contextObject = null;
                }
                $dataObject->{$context} = $contextObject;
            }
            $render->{"data"} = [$dataObject];
            return $render;
        }
        return null;
    }

    /**
     * Use Bread-First Search to run the Heuristics
     *
     * @param AiCrawler $node
     * @param callable $scoreHeuristicMethod
     */
    protected function bfs(AiCrawler &$node, $context, callable $scoreHeuristicMethod) {
        /**
         * Run the Heuristic, add to Considerations if scoring. This saves us from running BFS again later
         * to review our scores.
         */
        $content = $scoreHeuristicMethod($node, $this->payload[$context]);

        if ($content)
            $this->payload[$context]->push($content);

        /**
         * Later on, $considerations will be loaded into a Collection and sorted by score, but for now, the order of
         * occurrence matters.
         *
         * @todo Pass $n by reference and verify it actually worked (given the recursion)
         */
        $node->children()->each(function ($n, $i) use ($context, $scoreHeuristicMethod) {
            if ($n)
                self::bfs($n, $context, $scoreHeuristicMethod);
        });
    }

    /**
     * Last chance to examine all the considerations and augment items in the payload.
     *
     * @return $this
     */
    public function choose(callable $callback = null) {
        $sanitizers = $this->getSanitizers();
        foreach($this->getHeuristics() as $context => $class) {
            if (!is_null($callback))
                $this->payload[$context] = $callback($this->payload[$context], $context);

            if (array_key_exists($context, $sanitizers) && class_exists($class))
                $this->payload[$context] = call_user_func_array(
                    [$sanitizers[$context], "sanitize"],
                    [$this->payload[$context]]
                );
            $this->payload[$context]->sortByScore($context);
        }
        return $this;
    }

    /**
     * Automagically get the Sanitizer classes based on the Heuristics provided. This function will fail silently if
     * your class is not found.
     *
     * @return array
     */
    public function autoGetSanitizers() {
        $validSanitizers = [];
        $copyHeuristics = $this->getHeuristics();
        array_walk($copyHeuristics, function($value, $key) use(&$validSanitizers) {
            $s = str_replace("Heuristics", "Sanitizers", $value);
            $s = str_replace("Heuristic", "Sanitizer", $s);
            if (class_exists($s))
                $validSanitizers[$key] = $s;
            elseif(class_exists("\\Dan\\AiCrawler\\Sanitizers\\".$s))
                $validSanitizers[$key] = "\\Dan\\AiCrawler\\Sanitizers\\".$s;
        });
        return $validSanitizers;
    }

    /**
     * @return mixed
     */
    public function getHeuristics() {
        return $this->heuristics;
    }

    /**
     * @param mixed $heuristics
     *
     * @return $this
     */
    public function setHeuristics($heuristics) {
        $this->heuristics = $heuristics;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSanitizers() {
        return count($this->sanitizers) ? $this->sanitizers : $this->autoGetSanitizers();
    }

    /**
     * @param mixed $sanitizers
     *
     * @return $this
     */
    public function setSanitizers($sanitizers) {
        $this->sanitizers = $sanitizers;
        return $this;
    }

    /**
     * @return array(Considerations)
     */
    public function getPayload($context = null)
    {
        if (is_null($context))
            return $this->payload;
        elseif(array_key_exists($context, $this->payload))
            return $this->payload[$context];
        else
            return null;
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }


    /**
     * @param mixed $html
     *
     * @return $this
     */
    public function setHtml($html)
    {
        if ($html instanceof AiCrawler) {
            $this->html = $html;
        } else {
            $html = trim($html);
            if (strtolower(substr($html, 0, 4)) == "http") {
                $source = Source::both($html, $this->config->get("curl"));
                $html = $source->getSource();
            }
            $this->html = new AiCrawler($html);
        }
        return $this;
    }

}