<?php

namespace AiCrawlerTests;

use Dan\AiCrawler\AiCrawler;
use PHPUnit_Framework_TestCase;

/**
 * Class TestCrawler
 *
 * @package Heuristics
 * @author Dan Richards <drichardsri@gmail.com>
 */
class TestCrawler extends AiCrawler
{
    /**
     * @param null $node
     * @param null $currentUri
     * @param null $baseHref
     */
    public function __construct($node = null, $currentUri = null, $baseHref = null)
    {
        parent::__construct($node, $currentUri, $baseHref);
    }
}

/**
 * Class HeuristicsTestCase
 *
 * @package Heuristics
 * @author Dan Richards <drichardsri@gmail.com>
 */
class HeuristicsTestCase extends PHPUnit_Framework_TestCase
{
    public $crawler;
    public $example = '
            <!doctype html>
            <html>
                <head>
                    <title>Example Domain</title>

                    <meta charset="utf-8" />
                    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1" />
                    <style type="text/css">
                        body {
                            background-color: #f0f0f2;
                            margin: 0;
                            padding: 0;
                            font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
                        }
                        div {
                            width: 600px;
                            margin: 5em auto;
                            padding: 50px;
                            background-color: #fff;
                            border-radius: 1em;
                        }
                        a:link, a:visited {
                            color: #38488f;
                            text-decoration: none;
                        }
                        @media (max-width: 700px) {
                            body {
                                background-color: #fff;
                            }
                            div {
                                width: auto;
                                margin: 0 auto;
                                border-radius: 0;
                                padding: 1em;
                            }
                        }
                    </style>
                </head>

                <body>
                    <div>
                        <h1>Example Domain</h1>
                        <p>This domain is established to be used for illustrative examples in documents. You may use this
                        domain in examples without prior coordination or asking for permission.</p>
                        <p><a href="http://www.iana.org/domains/example">More information...</a></p>
                    </div>
                </body>
            </html>';

    /**
     * Prep $crawler with a fresh instance of TestCrawler
     */
    public function setUp()
    {
        $this->crawler = new TestCrawler($this->example);
    }

    /**
     * Clear the crawler.
     */
    public function tearDown()
    {
        $this->crawler = null;
    }
}
