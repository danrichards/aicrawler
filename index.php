<?php
include 'vendor/autoload.php';

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
 * Return / Output a heading, article content (no html), and image (url).
 *
 * Auxiliary Goals
 *
 * 1. Build some helper functions which will scrape all the relevant URLs
 * from a web page that goto respective articles on that web page.
 *
 * 2. Output a news summary for the user.
 */

pre("ok");