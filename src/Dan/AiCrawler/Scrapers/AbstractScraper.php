<?php namespace Dan\AiCrawler\Scrapers;

use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Considerations;
use Dan\AiCrawler\Support\Exceptions\HeuristicConventionViolatedException;
use Dan\AiCrawler\Support\Exceptions\HeuristicDoesNotExistException;
use Dan\AiCrawler\Support\Exceptions\HeuristicsNotDefinedException;

abstract class AbstractScraper {

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
     * It may be advantageous to augment our $html node tree before running the scraper. This methods exists so we
     * can make accommodations for anomalies (ie. shitty html conventions).
     *
     * @see Any website on the AOL network :p
     *
     * @param AiCrawler $top
     * @return $this
     */
    public function prep() {
        return $this;
    }

    /**
     * Bread-first search, running your heuristics and scoring the nodes. Considerations are gathered into the payload.
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
                        Make sure it's in the \\AiCrawler\\Heuristics\\ namespace. Otherwise, provide the
                        fully-qualified namespace path.");
            }
        } else {
            throw new HeuristicsNotDefinedException("You may not run scrape() until you've defined some Heuristics to
                use. Use AbstractScraper::setHeuristics()");
        }
        return $this;
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
     * @return ["context" => Considerations]
     */
    public function choose(callable $callback = null) {
        $sanitizers = $this->getSanitizers();
        foreach($this->getHeuristics() as $context => $class) {
            if (!is_null($callback))
                $this->payload[$context] = $callback($this->payload[$context], $context);

            if (array_key_exists($context, $sanitizers) && class_exists($class))
                $this->payload[$context] = call_user_func_array(
                    array($class, "sanitize"),
                    array($this->payload[$context])
                );
            $this->payload[$context]->sortByScore($context);
        }
        return $this->getPayload();
    }

    /**
     * Automagically get the Sanitizer classes based on the Heuristics provided. This function will fail silently if
     * your class is not found.
     *
     * @return array
     */
    public function autoGetSanitizers() {
        $validSanitizers = [];
        array_walk($this->getHeuristics(), function($value, $key) use(&$validSanitizers) {
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
        $this->html = $html;
        return $this;
    }

}