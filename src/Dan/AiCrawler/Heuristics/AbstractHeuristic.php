<?php namespace Dan\AiCrawler\Heuristics;

use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Considerations;

/**
 * All heuristics will implement these methods or override them with respect to their contexts.
 *
 * @package AiCrawler\Heuristics
 */
abstract class AbstractHeuristic implements HeuristicInterface
{

    /**
     * Score your nodes.
     *
     * @param AiCrawler $node
     * @param Considerations $c
     * @return Considerations
     */
    abstract public static function score(AiCrawler &$node, Considerations $c);

    /**
     * What do we want available in our API responses? By Default, we give as much as possible and leave it to your
     * Heuristics to override. The object is returned in an array so we may flatten $extra data from our Scraper into
     * each data element.
     *
     * If you want to return a set, you definitely need to override render() in your Heuristic.
     *
     * @param AiCrawler $node
     * @return mixed
     */
    public static function render(AiCrawler $node, $context, $scraperExtra = []) {
        $render = new \stdClass();
        $render->text = $node->text();
        $render->html = $node->html();

        /**
         * Add any extra data from Scraper or Node
         */
        if ($extra = $node->getExtra()) {
            foreach ($extra as $key => $item)
                $render->{$key} = $item;
        }
        if (count($scraperExtra)) {
            foreach ($scraperExtra as $key => $item)
                $render->{$key} = $item;
        }

        $render->attr = (object) $node->getAttributes(['id', 'class', 'name', 'alt', 'title', 'value', 'label', 'src', 'rel']);

        $render->crawler = new \stdClass();
        $render->crawler->score = $node->getScoreTotal($context);
        $render->crawler->element = $node->nodeName();
        $render->crawler->parents = $node->parents()->count();
        $render->crawler->children = $node->children()->count();
        $render->crawler->siblings = $node->siblings()->count();

        $render->lexical = new \stdClass();
        $render->lexical->characters = $node->numCharacters();
        $render->lexical->words = $node->numWords();
        $render->lexical->sentences = $node->numSentences();
        $render->lexical->paragraphs = $node->numParagraphs();

        return $render;
    }

}