#AiCrawler Web Scraping

A web scraping pattern using heuristics with Symfony Components.


## Table of Contents

- [Features](#features)
- [Install with Composer](#install)
- [Usage with Examples](#usage)
- [Release Notes](#notes)
- [Contributing](#contributing)
- [License](#license)


##Features<a name="features"></a>

The AiCrawler package has the responsibility of making boolean assertions on a node in the HTML DOM. It comes with a straight-forward data point trait which will record the results of your heuristics (rules) for a given "item" or context.

##Install with Composer<a name="install"></a>

>$ composer require dan/aicrawler dev-master


##Usage<a name="usage"></a>
   
### What does it do?

The Symfony DOMCrawler does great job building special-purpose scrapers. AiCrawler provides the means to build intelligent all-purpose scrapers, or something specialized. The over-arching goal for this extension is to take a AI approach towards scoring nodes on the DOM, based on interesting characteristics that can be defined by a heuristic. 

Scoring nodes using heuristics allows us to use our creativity to do some of the following things: 

1. Learn / gather something special in a large arrangement of data.
2. Reduce dependency on the DOM for finding our scraper's payload.

### Why all the statics?

The `Heuristics` class is static to minimize the impact it may have on memory in subsequent abstractions. All public methods are boolean, so it's essentially a tool for mapping functions (heuristics or rules) to nodes on the DOM. Usage of `Scoreable` in our abstractions impact will how useful our scrapers become.

You will also notice that each method has a similar interface. e.g.

    public static function characters(AiCrawler &$node, array $args = [])

Passing the node will allow our heuristics access to anything they might need. All argument are passed as an array. This will later simplify storing the criteria for our heuristics in a configuration.

Another in-place convention is using a static object property for argument defaults. e.g.

    protected static $characters = [
        'characters' => true,
    ];

Any missing arguments will fall back to the class property. There is also a property called `$defaults` for more general properties which acts a secondary fall back. If a heuristic requires an argument it cannot find, an `InvalidArgumentException` will be thrown.

## Version 0.0.1<a name="notes"></a>

- A heuristic pattern for building web scrapers.
- A good set of heuristics to get you started.


## Todo<a name="todo"></a>

- [Search Github](https://github.com/danrichards/aicrawler/search?utf8=%E2%9C%93&q=todo)
- Finish related projects. See [AiResponders](https://github.com/danrichards/airesponders), [AiScrapers](https://github.com/danrichards/aiscrapers), and [Larascrape](https://github.com/danrichards/larascrape).


## Contributing<a name="contributing"></a>

1. [Fork](https://github.com/danrichards/aicrawler) this [project](https://github.com/danrichards/aicrawler) on GitHub.
2. Existing [unit tests](https://github.com/danrichards/aicrawler/tree/master/tests) must pass.
3. Contributions must be unit tested.
4. New heuristics should be portable (have few or no dependencies).
5. New heuristics should have helpful doc blocks.
6. Submit a pull request.


### Documentation<a name="documentation"></a>

- Follow [PSR-2](http://www.php-fig.org/psr/psr-2/).
- Add PHPDoc blocks for all classes, methods, and functions
- Omit the `@return` tag if the method does not return anything
- Add a blank line before `@param`, `@return` or `@throws`

Any issues, please [report here](https://github.com/danrichards/aicrawler/issues)


## License<a name="license"></a>

AiCrawler is free software distributed under the terms of the [MIT license](http://opensource.org/licenses/MIT).