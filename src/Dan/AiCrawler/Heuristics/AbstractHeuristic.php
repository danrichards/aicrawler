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
     * What do we want available in our API responses? By Default, we give as much as possible and leave it to your
     * Heuristics to override. The object is returned in an array so we may flatten $extra data from our Scraper into
     * each data element.
     *
     * If you want to return a set, you definitely need to override response() in your Heuristic.
     *
     * @param AiCrawler $node
     * @param $context string
     * @param $scraperExtra array
     *
     * @return \stdClass()
     */
    public static function response(AiCrawler $node, $context, $scraperExtra = []) {
        $response = new \stdClass();
        $response->text = $node->text();
        $response->html = $node->html();

        /**
         * Add any extra data from Scraper or Node
         */
        if ($extra = $node->getExtra()) {
            foreach ($extra as $key => $item)
                $response->{$key} = $item;
        }
        if (count($scraperExtra)) {
            foreach ($scraperExtra as $key => $item)
                $response->{$key} = $item;
        }

        $response->attr = (object) $node->getAttributes(['id', 'class', 'name', 'alt', 'title', 'value', 'label', 'src', 'rel', 'href']);

        $response->crawler = new \stdClass();
        $response->crawler->score = $node->getScoreTotal($context);
        $response->crawler->element = $node->nodeName();
        $response->crawler->parents = $node->parents()->count();
        $response->crawler->children = $node->children()->count();
        $response->crawler->siblings = $node->siblings()->count();

        $response->lexical = new \stdClass();
        $response->lexical->characters = $node->numCharacters();
        $response->lexical->words = $node->numWords();
        $response->lexical->sentences = $node->numSentences();
        $response->lexical->paragraphs = $node->numParagraphs();

        return $response;
    }

}