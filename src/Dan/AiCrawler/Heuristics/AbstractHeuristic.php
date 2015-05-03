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
     *
     *
     * @param AiCrawler $node
     * @return mixed
     */
    public static function render(AiCrawler $node, $context, $extra = []) {
        $render = new \stdClass();

        foreach ($extra as $key => $value)
            $render->{$key} = $value;

        $render->text = $node->text();
        $render->html = $node->html();

        $render->attr = (object) $node->getAttributes(['id', 'class', 'name', 'alt', 'title', 'value', 'label']);

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

        return new $render;
    }

}