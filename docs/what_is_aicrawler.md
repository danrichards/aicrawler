# What is AiCrawler?

A web scraping pattern using heuristics with [Symfony DOM Crawler](http://symfony.com/doc/current/components/dom_crawler.html).

The [AiCrawler package](https://github.com/danrichards/aicrawler) has the responsibility of making boolean assertions on a node in the HTML DOM. It comes with a straight-forward data point trait which will record the results of your heuristics (rules) for a given "item" or context.

Scoring nodes using heuristics allows us to use our creativity to do some of the following things: 
1. Learn / gather something special in a large arrangement of data.
2. Reduce dependency on the DOM for finding our scraper's payload.