<?php

use Dan\AiCrawler\Scrapers\BlogScraper;
use Dan\AiCrawler\Support\AiConfig;
use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Source;
use Dan\AiCrawler\Support\Exceptions\SourceNotFoundException;
use Dan\Core\Helpers\Dump;
use Dan\Core\Helpers\RegEx;

include 'vendor/autoload.php';

$test = new Dan\AiCrawler\Support\AiCrawler();

/**
 * Dan Richards                                         CSC 481 Hamel
 * Final Project                                        Spring 2015
 *
 * Use our knowledge of search and heuristics to design a article scraper.
 *
 * Goal (in terms of input and output):
 *
 * Input a URL (ideally, but not necessarily to an article).
 *
 * Return / Output article headline (text), content (text), and image (url).
 *
 * Auxiliary Goals
 *
 * 1. Build some helper functions which will scrape all the relevant URLs
 * from a web page that goto respective articles on that web page.
 *
 * 2. Output a news summary for the user.
 */

$url = isset($_POST["url"]) ? $_POST["url"] : "http://www.example.com/";
?>

<h1>AiCrawler Test</h1>
<form action="index.php" method="post">
    <fieldset>
        <label for="url">
            URL:
        </label>
        <input type="text" name="url" value="<?php echo $url; ?>" />
        <input type="submit" value="Submit" />
    </fieldset>
</form>
<br /><br /><br />

<?php

/**
 * Download, Scrape the Crawler, Output
 */
try {
    $blog = new BlogScraper($url);
    $content = $blog->setExtra("link", $url)->scrape()->choose()->getPayload("content");

    if ($content->count()) {
        $first = $content->first();
        echo $first->nodeName() . ", Scoring ".number_format($first->getScoreTotal("content"), 1)." amongst " .
            $content->count() . " considerations. ";
        /**
         * Dump and additional info that was collected in our Considerations object
         */
        echo "<br /><hr />Extra: ".Dump::micro($first->getExtra(), true);

        /**
         * Output a preview of the text scraped and all the HTML
         */
        echo "<br /><hr />Text: ";
        echo substr(RegEx::removeExtraneousWhitespace($first->text()), 0, 500)."...";
        echo "<br /><hr />HTML: ";
        echo RedEx::removeExtraneousWhitespace($first->html());
    } else {
        echo "Sorry, no content found.";
    }
} catch (SourceNotFoundException $e) {
    echo "Unable to download the source with curl. ".$e->getMessage();
} catch (\InvalidArgumentException $e) {
    echo "A crawler method was called with a bad argument. ".$e->getMessage();
}