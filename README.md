#AiCrawler Web Scraping

A web scraping pattern using heuristics with Symfony Components.


## Table of Contents

- [Features](#features)
- [Install with Composer](#install)
- [Usage with Examples](#usage)
- [Release Notes](#notes)
- [Todo](#todo)
- [Contributing](#contributing)
- [License](#license)


##Features<a name="features"></a>


##Install with Composer<a name="install"></a>

>$ composer require dan/aicrawler dev-master
    
*Optionally, you may want to copy the `crawl` file to your base path (where your `composer.json` resides)*
    
##Usage<a name="usage"></a>

### Use Symfony Commands for dev / testing.

Try running some of the following commands:

- Provide a list of commands.

	>$ php vendor/dan/aicrawler/crawl
	
	or if you copied / linked the crawl file to your project's base path:

	>$ php crawl

- Get help with a specific command.

	>$ php crawl help dom:bfs

- Output details for the nodes (bread-first), only traversing section, div, p (html, body included automatically) and show all details (-da)

	>$ php crawl dom:bfs http://www.latimes.com/sports/sportsnow/la-sp-sn-jon-lester-throws-glove-to-put-out-batter-20150419-story.html --only="section,div,p" -da

- See [Symfony's filter()](http://symfony.com/doc/current/components/dom_crawler.html) method in action.

    >$ php crawl dom:inspect http://mashable.com/2015/04/20/magic-8-ball-app/ --filter="header > h1"

- I've written some Heuristics and Commands for the purposes of scraping a blog. The following will list qualifying content nodes that score higher than 0.5 with our `ContentHeuristic`. You may choose to omit `--min=0.5` and only the top result will output.

    >$ php crawl blog:content http://www.huffingtonpost.com/2015/04/19/leaked-game-of-thrones-hbo_n_7096000.html --min=0.5


Review the code for the commands, it's a good way to understand how the AiCrawler package works.

### How do I start scraping my own stuff?

The AiCrawler package is designed to be extensible, when writing new Scrapers, the bulk of your work will be writing clever Heuristics that implement `HeuristicInterface`. All your Heuristic class / file names should end with `Heuristic`. Each Heuristic's goal is to find a single dom element (or a repetition of a certain type of elements). If your scraper needs to find multiple pieces of information (i.e. multiple elements) you will define a Heuristic for each. 

The job of a Heuristic is to `score()` a node. Your scrapers will iterate through the AiCrawler (dom tree) and score the node. During iteration, your Heuristics have access to any previous considerations that have been scored.

### Let's examine the BlogScraper

We'll start from the outside (our object instantiations and method calls) and work our way inward.

####In [`HeadlineCommand.php`](https://github.com/danrichards/aicrawler/blob/master/src/Dan/AiCrawler/Console/Blog/HeadlineCommand.php)

We instantiate a new `BlogScraper`.

	// Provide a URL, source code or AiCrawler object and our scraper will bake a AiCrawler object.
	
	$blog = new BlogScraper($url);

Now, run our scraper.  We'll discuss `choose()` later.

	$payload = $blog->scrape()->choose();

`$payload` becomes and associative array of [Collections](https://github.com/illuminate/support/blob/master/Collection.php) ([`Considerations.php`](https://github.com/danrichards/aicrawler/blob/master/src/Dan/AiCrawler/Support/Considerations.php))

	// something like this.
	$payload = [
		'headline' => (object) Considerations (which is a Collection)
		'content' => (object) Considerations
		// and so on for however many Heuristics you feed your `Scraper`.
	];

At this point, here are some things we have access to in our output.

	$headlines = $payload['headline'];
	foreach ($headlines as $h) {
        print "Element: " . $h->nodeName() . "\n";
        print "Score :" . number_format($h->getScoreTotal("headline"), 1) . "\n";
        print "Text: " . regex_remove_extraneous_whitespace($h->text()));
    }

####Let's dig deeper into `scape()`, here is the [`BlogScraper`](https://github.com/danrichards/aicrawler/blob/master/src/Dan/AiCrawler/Scrapers/BlogScraper.php) class.

	class BlogScraper extends AbstractScraper implements ScraperInterface {
	    
	    protected $blogHeuristics = [
	        "headline" => "HeadlineHeuristic",
	        "content" => "ContentHeuristic",
	        "image" => "ClosestImageHeuristic"
	    ];
	
	    /**
	     * Default Configuration
	     *
	     * @param AiCrawler|html|url $node
	     * @param array $config
	     */
	    function __construct($node = null, $config = []) {
	        parent::__construct($node, $config);
	    }
	
	    /**
	     * Run Heuristics and generate the payload.
	     *
	     * @return $this
	     */
	    public function scrape() {
	        $this->setHeuristics($this->blogHeuristics);
	        return parent::scrape();
	    }
	    
	}

Our `BlogScraper` class is pretty minimal because `AbstractScraper` does most of heavy-lifting. *If you have to design something really crazy, just make sure it implements `ScraperInterface`*

Here is a quick run-down of `BlogScraper`:

1. `$blogHeuristics` defines what classes for each respective context (element goal).
	- When you write your own Heuristics, include the fully-qualified name spaced path.
	- e.g. `"something" => "\\Acme\\MyHeuristics\\SomethingHeuristic"`
2. Our `__construct(...)` generates our [AiCrawler](https://github.com/danrichards/aicrawler/blob/master/src/Dan/AiCrawler/Support/AiCrawler.php) object (extends [Symfony DomCrawler](http://symfony.com/doc/current/components/dom_crawler.html)).
3.  Our `scrape()` method sets the Heuristics we're going to use and calls `parent::scrape()`

####Here is some pseudo-code for [`AbstractScraper`](https://github.com/danrichards/aicrawler/blob/master/src/Dan/AiCrawler/Scrapers/AbstractScraper.php) class.

	abstract class AbstractScraper {
	    protected $config, $html, $payload = [], $heuristics = []; $sanitizers = [];
	
		function __construct($node = null, $config = []) {
		    // set parent node and config
	    }
	
	    public function prep() {
			// optional prep work for `$html` (the node structure)
	    }
	
	    public function scrape(){
		    // for each context
			    // find the respective Heuristic
			    // perform bread-first search with that Heuristic, scoring all the nodes
	        // return this (so we may method chain)
	    }	
	
	    protected function bfs(AiCrawler &$node, $context, callable $scoreHeuristicMethod) {
		    // score the Heuristic
			// if scoring, add to payload
			// call bfs recursively
	    }

Here is the `choose()` method I'd said we'd get back to:
	
	    public function choose(callable $callback = null) {
		    // for each context
			    // run an optional callback for augmenting your Collections
		        // run sanitizers, in any (detected just like Heuristics)
		        // sort each context Collection by score descending
	        // return payload
	    }
	
	    // Getters and setters
	
	}

#### View some Heuristics

1. [ContentHeuristic](https://github.com/danrichards/aicrawler/blob/master/src/Dan/AiCrawler/Heuristics/ContentHeuristic.php)
2. [HeadlineHeuristic](https://github.com/danrichards/aicrawler/blob/master/src/Dan/AiCrawler/Heuristics/ContentHeuristic.php)
3. [ClosestImageHeuristic](https://github.com/danrichards/aicrawler/blob/master/src/Dan/AiCrawler/Heuristics/HeadlineHeuristic.php)
4. [HeuristicInterface](https://github.com/danrichards/aicrawler/blob/master/src/Dan/AiCrawler/Heuristics/HeuristicInterface.php)


## Version 0.0.1<a name="notes"></a>

- A heuristic pattern for building web scrapers.
- Heuristics written to scrape an article headline and content.


## Todo<a name="todo"></a>

- [Search Github](https://github.com/danrichards/aicrawler/search?utf8=%E2%9C%93&q=todo)
- I'm working on micro-service built in [Lumen](http://lumen.laravel.com/) that will provide an API (routing and json) for interacting with Scrapers and hopefully some commands for quickly scaffolding the code (i.e. routing and controllers).


## Contributing<a name="contributing"></a>

Please fork this project on [GitHub](https://github.com/danrichards/aicrawler) and share any useful Heuristics / 
Scrapers or Commands you've written. Then submit a PR :)


### Documentation<a name="documentation"></a>

- Follow PSR-2 Coding standards.
- Add PHPDoc blocks for all classes, methods, and functions
- Omit the `@return` tag if the method does not return anything
- Add a blank line before `@param`, `@return` and `@throws`

Any issues, please [report here](https://github.com/danrichards/aicrawler/issues)


## License<a name="license"></a>

AiCrawler is free software distributed under the terms of the [MIT license](http://opensource.org/licenses/MIT).