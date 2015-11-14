#AiCrawler

Leverage Ai design patterns by using heuristics with the [Symfony DOMCrawler](http://symfony.com/doc/current/components/dom_crawler.html).

Please crawl on over to the [docs](https://github.com/danrichards/aicrawler-docs) which are also available as a [gitbook](https://danrichards.gitbooks.io/aicrawler/content/index.html).

[![GitBook](https://avatars2.githubusercontent.com/u/7111340?v=3&s=200)](https://danrichards.gitbooks.io/aicrawler/content/index.html)

- [QuickStart](#quickstart)
- [Todo](#todo)
- [Version](#version)
- [Contributing](#contributing)
- [Documentation](#documentation)
- [License](#license)


<a name="quickstart"></a>
## Quickstart

The [AiCrawler](AiCrawler/README.md) package has the responsibility of making boolean assertions on a node in the HTML DOM. It comes with a straight-forward [data point trait](AiCrawler/scorable.md) which will record the results of your [heuristics (rules)](AiCrawler/Heuristics/README.md) for a given "item" or context.

### Install with Composer

```
$ composer require dan/aicrawler dev-master
```

### Trivial example

```
$crawler = new AiCrawler('<html>...</html>');

$node = $crawler->filter('div[id="content-start"]');
$args = ['words' => 15];

// Does the content have at least 15 words?
$assertion = Heuristics::words($node, $args); // true / false
```

### A more expressive example

```
$crawler = new AiCrawler("<html>...</html>");

$args = [
    'elements' => [
        "elements" => "/p/ /blockquote/ /(u|o)l/ /h[1-6]/",
        "regex" => true,
        'words' => [
            'words' => 15,
            'descendants' => true,
            'words2' => [
                'words' => "/(cod(ing|ed|e)|program|language|php)/",
                'regex' => true,
                'descendants' => true
            ]
        ]
    ],
    'matches' => 3
]


/**
 * Do at least 3 of this div's children which are p, blockquote, ul, ol or any
 * h element AND contain at least 15 words (including text from their 
 * descendants) AND words such as coding, coded, code, program, language, php 
 * (including text from its descendants).
 */
$crawler->filter("div")->each(function(&$node) use ($args) {
    if (Heuristics::children($node, $args) {
        $node->setDataPoint("example", "words", 1);
    }
});
```
Sound interested? Read on about the [`Heuristics`](https://danrichards.gitbooks.io/aicrawler/content/AiCrawler/Heuristics/index.html) class or go right to a [similar example](https://danrichards.gitbooks.io/aicrawler/content/AiCrawler/Heuristics/index.html#nested) with complete notes.


<a name="notes"></a>
## Version 0.0.1

- A `Heuristics` class with some cool rules to get you started.
- A `Scorable` trait is on our `AiCrawler`class so there is a pattern for data points.
- A `Extra` trait is on our `AiCrawler` class so there is a pattern for storing extra data.


<a name="todo"></a>
## Todo

- [Search Github](https://github.com/danrichards/aicrawler/search?utf8=%E2%9C%93&q=todo)
- Finish related projects. See [AiResponders](https://github.com/danrichards/airesponders), [AiScrapers](https://github.com/danrichards/aiscrapers), and [Larascrape](https://github.com/danrichards/larascrape).


<a name="contributing"></a>
## Contributing

1. [Fork](https://github.com/danrichards/aicrawler) this [project](https://github.com/danrichards/aicrawler) on GitHub.
2. Existing [unit tests](https://github.com/danrichards/aicrawler/tree/master/tests) must pass.
3. Contributions must be unit tested.
4. New heuristics should be portable (have few or no dependencies).
5. New heuristics should have helpful doc blocks.
6. Submit a pull request.
7. See guide on [extending `Heuristics`](AiCrawler/Heuristics/extending.md) for special heuristics.


<a name="documentation"></a>
## Documentation

- Follow [PSR-2](http://www.php-fig.org/psr/psr-2/).
- Add PHPDoc blocks for all classes, methods, and functions
- Omit the `@return` tag if the method does not return anything
- Add a blank line before `@param`, `@return` or `@throws`

Any issues, please [report here](https://github.com/danrichards/aicrawler/issues)


<a name="license"></a>
## License

AiCrawler is free software distributed under the terms of the [MIT license](http://opensource.org/licenses/MIT).