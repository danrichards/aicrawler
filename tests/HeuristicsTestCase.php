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
//class TestCrawler extends AiCrawler
//{
//    /**
//     * @param null $node
//     * @param null $currentUri
//     * @param null $baseHref
//     */
//    public function __construct($node = null, $currentUri = null, $baseHref = null)
//    {
//        parent::__construct($node, $currentUri, $baseHref);
//    }
//}

/**
 * Class HeuristicsTestCase
 *
 * @package Heuristics
 * @author Dan Richards <drichardsri@gmail.com>
 */
class HeuristicsTestCase extends PHPUnit_Framework_TestCase
{
    public $crawler;

    /**
     * Prep $crawler with a fresh instance of TestCrawler
     */
    public function setUp()
    {
        $this->crawler = new AiCrawler($this->example);
    }

    /**
     * Clear the crawler.
     */
    public function tearDown()
    {
        $this->crawler = null;
    }

    /**
     * Example document we'll use for testing.
     *
     * @var string
     * @see http://radar.oreilly.com/2015/04/open-source-won-so-whats-next.html
     */
    public $example = '
        <!DOCTYPE html>
        <html lang="en-US">
           <head></head>
           <body class="single single-post postid-75505 single-format-standard et_monarch">
              <div id="fixed-top"><span>Menu</span></div>
              <nav id="mobi" class="menu-main-nav-container">
                 <ul id="menu-main-nav" class="menu">
                    <li id="menu-item-48418" class="first menu-item menu-item-type-custom menu-item-object-custom menu-item-48418"><a href="http://oreilly.com/">Home</a></li>
                    <li id="menu-item-48419" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48419"><a href="http://shop.oreilly.com/">Shop Video Training &#038; Books</a></li>
                    <li id="menu-item-48420" class="current_page_item menu-item menu-item-type-custom menu-item-object-custom menu-item-home menu-item-has-children menu-item-48420"><a href="http://radar.oreilly.com/">Radar</a></li>
                    <li id="menu-item-48422" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48422"><a href="http://www.safaribooksonline.com/?utm_source=oreilly&#038;utm_medium=referral&#038;utm_campaign=publisher&#038;utm_content=nav">Safari Books Online</a></li>
                    <li id="menu-item-48423" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48423"><a href="http://oreilly.com/conferences/">Conferences</a></li>
                 </ul>
              </nav>
              <div id="wrapper" class="hfeed ">
                 <div class="navheaderbg">
                    <div>
                       <div id="header">
                          <div class="header-top">
                             <a href="http://oreilly.com/" title="oreilly.com">oreilly.com</a>
                             <div class="clear"></div>
                          </div>
                          <div class="head-inner">
                             <div class="logo">
                                <a href="http://radar.oreilly.com/" title="O&#039;Reilly Radar" rel="home">O&#039;Reilly Radar</a>
                             </div>
                             <div id="search-box">
                                <div id="header-social-wrap">
                                   <div id="header-social-btn">
                                      <a class="rss-btn"  href="http://feeds.feedburner.com/oreilly/radar/atom" >RSS Feed</a>
                                      <a class="twitter-btn" href="http://twitter.com/radar">Twitter</a>
                                      <a class="fb-btn" href="https://www.facebook.com/OReillyRadar">Facebook</a>
                                      <a class="gps-btn" href="https://plus.google.com/105451978536505503907" >Google+</a>
                                      <a class="yt-btn" href="http://www.youtube.com/user/OreillyMedia" >Youtube</a>
                                      <div class="clear"></div>
                                   </div>
                                </div>
                                <div class="yui-skin-sam">
                                   <form name="searchform" method="get" id="search-form" action="http://search.oreilly.com/">
                                      <div class="search">
                                         <fieldset>
                                            <span id="search-input">
                                               <div class="searchInput">
                                                  <span id="search-field"><input type="text" value="Search" name="q" maxlength="64" id="q" onfocus="this.value=checkIfDefault(this.value);"></span>
                                               </div>
                                               <span id="search-button">
                                               <input type="image" value="SEARCH" src="http://cdn.oreillystatic.com/images/sitewide-headers/search_btn.gif" border="0" style="float:left" alt="Search" align="top">
                                               <input type="hidden" name="tmpl" value="radar">
                                               </span>
                                            </span>
                                         </fieldset>
                                      </div>
                                   </form>
                                </div>
                             </div>
                             <div class="clear"></div>
                          </div>
                          <div class="clear"></div>
                       </div>
                       <!-- #header -->
                       <div id="access" role="navigation">
                          <div class="menu-header">
                             <ul id="menu-main-nav-1" class="menu">
                                <li class="first menu-item menu-item-type-custom menu-item-object-custom menu-item-48418"><a href="http://oreilly.com/">Home</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48419"><a href="http://shop.oreilly.com/">Shop Video Training &#038; Books</a></li>
                                <li class="current_page_item menu-item menu-item-type-custom menu-item-object-custom menu-item-home menu-item-has-children menu-item-48420">
                                   <a href="http://radar.oreilly.com/">Radar</a>
                                   <ul class="sub-menu">
                                      <li id="menu-item-67912" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home menu-item-67912"><a href="http://radar.oreilly.com/">Radar</a></li>
                                      <li id="menu-item-67913" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-67913"><a href="http://animals.oreilly.com/">Animals</a></li>
                                   </ul>
                                </li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48422"><a href="http://www.safaribooksonline.com/?utm_source=oreilly&#038;utm_medium=referral&#038;utm_campaign=publisher&#038;utm_content=nav">Safari Books Online</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48423"><a href="http://oreilly.com/conferences/">Conferences</a></li>
                             </ul>
                          </div>
                          <div class="clear"></div>
                       </div>
                       <!-- #access -->
                       <div class="clear"></div>
                       <div id="menu-wrap">
                          <div class="catname"><a href="http://radar.oreilly.com/programming">Programming</a></div>
                          <div id="sub">
                             <div class="nav">
                                <div class="name">More Topics <span class="arrow-down"></span></div>
                                <div class="sub-menu">
                                   <ul class="nav-bar">
                                      <li><a href="http://radar.oreilly.com/data">Data</a></li>
                                      <li><a href="http://radar.oreilly.com/design">Design</a></li>
                                      <li><a href="http://radar.oreilly.com/emerging-tech">Emerging Tech</a></li>
                                      <li><a href="http://radar.oreilly.com/iot">IoT</a></li>
                                      <li><a href="http://radar.oreilly.com/programming">Programming</a></li>
                                      <li><a href="http://radar.oreilly.com/webops-perf">Web Ops & Performance</a></li>
                                      <li><a href="http://radar.oreilly.com/web-platform">Web Platform</a></li>
                                   </ul>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="clear"></div>
                       <!-- PROTOTYPE MESSAGE CODE -->
                       <div id="visit-beta">
                          <div class="table">
                             <a class="hide" href="#"></a>
                             <a class="message" href="https://www.oreilly.com/topics/software-engineering">We\'re in the process of moving Radar to the new oreilly.com. <span class="underline">Check it out</span>.</a>
                          </div>
                       </div>
                       <div class="clear"></div>
                       <!-- PROTOTYPE MESSAGE CODE End-->
                    </div>
                 </div>
                 <div id="sww-content">
                    <div class="textwidget">
                       <!-- Either there are no banners, they are disabled or none qualified for this location! -->
                    </div>
                    <div class="clear"></div>
                 </div>
                 <div id="main">
                    <style type="text/css">
                       .single .hentry { padding-bottom:20px; }
        #content #newsletter { padding:0; margin:0 0 20px; border-bottom:1px solid #888; }
        #content #newsletter > h3 { margin:15px 0 0 0; padding:0; font-size:22px; line-height:1.1em; font-weight:bold; }
        #content #newsletter > h4 { margin:0 0 5px; padding:0; font-size:15px; line-height:1.3em; font-weight:normal; }
        #content #newsletter > p { margin:0 0 20px; padding:0; font-size:15px; line-height:1.3em; font-weight:normal; }
        </style>
                    <div id="container">
                       <div id="content" class="post_block" role="main">
                          <script src="http://s.radar.oreilly.com/wp-content/themes/radar/js/rs_embhl_v2_en_us.js" type="text/javascript"></script>
                          <script src="http://s.radar.oreilly.com/wp-content/themes/radar/js/jquery.cookie.js" type="text/javascript"></script>
                          <div id="post-75505" class="post-75505 post type-post status-publish format-standard has-post-thumbnail hentry category-programming tag-home tag-webopsfeaturedxpost tag-webplatformfeaturedxpost tag-be-a-software-engineer tag-expanded-view-of-mobile-development-and-data tag-integrate-open-source-culture-and-code tag-languages tag-learn-how-to-solve-problems tag-open-source tag-oscon tag-oscon-2015 tag-programming-2 tag-software-engineers">
                             <ul class="entry-tools">
                                <span class="tool-block">
                                   <li class="button print">
                                      <a target="_new" href="#" onclick="window.open(\'http://radar.oreilly.com/print/?print_bc=75505\',\'print\',\'width=800,height=600,menubar=no,status=no,location=yes,toolbar=yes,scrollbars=yes\'); return false;" rel="nofollow">Print</a>
                                   </li>
                                   <li class="button listen">
                                      <div id="readspeaker_button1" class="rs_skip en_us"> <a accesskey="L" href="http://app.readspeaker.com/cgi-bin/rsent?customerid=14&amp;lang=en_us&amp;readid=body-content&amp;url=http://radar.oreilly.com/2015/04/open-source-won-so-whats-next.html" target="_blank" onclick="readpage(this.href, \'content_start\'); return false;">Listen</a> </div>
                                   </li>
                                   <div id="content_start" style="display: block; "></div>
                                </span>
                             </ul>
                             <a href="http://radar.oreilly.com/rachelr">
                             <img src="http://cdn.oreillystatic.com/radar/images/people/photo_rachelr_s.jpg" class="au-photo" alt="" title="Rachel Roumeliotis" />
                             </a>
                             <!-- RSPEAK_START -->
                             <div id="body-content">
                                <h1 class="entry-title">Open source won, so what&#8217;s next?</h1>
                                <h2 class="subhead">What to expect at OSCON 2015.</h2>
                                <div class="entry-meta">
                                   <span class="meta-sep">by</span>
                                   <span class="author vcard"><a class="url fn n" rel="author" href="http://radar.oreilly.com/rachelr" title="View all posts by Rachel Roumeliotis">
            Rachel Roumeliotis</a></span> |
                                   <!-- RSPEAK_STOP -->
                                   <a href="http://twitter.com/rroumeliotis"> @rroumeliotis</a> |
                                   <a href="https://plus.google.com/u/0/117815609935161595398/posts"> +Rachel Roumeliotis</a> |
                                   <span class="comments-link utility-items"><a href="http://radar.oreilly.com/2015/04/open-source-won-so-whats-next.html#comments"><span class="dsq-postid" data-dsqidentifier="75505 http://radar.oreilly.com/?p=75505">Comment: 1</span></a></span>
                                   <span class="meta-sep utility-items"> | </span>
                                   <span class="entry-date">April 9, 2015</span>
                                </div>
                                <!-- .entry-meta -->
                                <div class="entry-utility social">
                                   <div class="et_social_inline et_social_mobile_on et_social_inline_bottom">
                                      <div class="et_social_networks et_social_autowidth et_social_slide et_social_rectangle et_social_left et_social_no_animation et_social_outer_dark">
                                         <ul class="et_social_icons_container">
                                            <li class="et_social_twitter"><a href="http://twitter.com/share?text=Open source won, so what&amp;#8217;s next?&#038;url=http%3A%2F%2Fradar.oreilly.com%2F2015%2F04%2Fopen-source-won-so-whats-next.html&#038;via=Radar" class="et_social_share" rel="nofollow" data-social_name="twitter" data-post_id="75505" data-social_type="share"><i class="et_social_icon et_social_icon_twitter"></i><span class="et_social_overlay"></span></a></li>
                                            <li class="et_social_googleplus"><a href="https://plus.google.com/share?url=http%3A%2F%2Fradar.oreilly.com%2F2015%2F04%2Fopen-source-won-so-whats-next.html&#038;t=Open source won, so what&amp;#8217;s next?" class="et_social_share" rel="nofollow" data-social_name="googleplus" data-post_id="75505" data-social_type="share"><i class="et_social_icon et_social_icon_googleplus"></i><span class="et_social_overlay"></span></a></li>
                                            <li class="et_social_facebook"><a href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fradar.oreilly.com%2F2015%2F04%2Fopen-source-won-so-whats-next.html&#038;t=Open source won, so what&amp;#8217;s next?" class="et_social_share" rel="nofollow" data-social_name="facebook" data-post_id="75505" data-social_type="share"><i class="et_social_icon et_social_icon_facebook"></i><span class="et_social_overlay"></span></a></li>
                                            <li class="et_social_linkedin"><a href="http://www.linkedin.com/shareArticle?mini=true&#038;url=http%3A%2F%2Fradar.oreilly.com%2F2015%2F04%2Fopen-source-won-so-whats-next.html&#038;title=Open source won, so what&amp;#8217;s next?" class="et_social_share" rel="nofollow" data-social_name="linkedin" data-post_id="75505" data-social_type="share"><i class="et_social_icon et_social_icon_linkedin"></i><span class="et_social_overlay"></span></a></li>
                                         </ul>
                                      </div>
                                   </div>
                                   <span class="comments-link utility-items"><a href="http://radar.oreilly.com/2015/04/open-source-won-so-whats-next.html#comments"><span class="dsq-postid" data-dsqidentifier="75505 http://radar.oreilly.com/?p=75505">Comment: 1</span></a></span>
                                   <div class="clear"></div>
                                </div>
                                <!-- .entry-utility -->
                                <div class="entry-content">
                                   Here is one entry content as the top of the Node.
                                   <!-- RSPEAK_START -->
                                   <p><img src="http://s.radar.oreilly.com/wp-files/2/2015/04/0415-oscon14-show-floor.jpg" alt="OSCON 2014 show floor" width="620" class="aligncenter size-full wp-image-75508" /></p>
                                   <p>Twenty years ago, open source was a cause. Ten years ago, it was the underdog. Today, it sits upon the Iron Throne ruling all it surveys. Software engineers now use open source frameworks, languages, and tools in almost all projects. </p>
                                   <p>When I was putting together the program for <a href="http://www.oscon.com/open-source-2015?intcmp=il-prog-confreg-update-os15_20150409_radar_oscon_15_announcement">OSCON</a> with the other <a href="http://www.oscon.com/open-source-2015/public/content/about#chairs">program chairs</a>, it occurred to me that by covering &#8220;just&#8221; open source, we weren&#8217;t really leaving out all that much of the software landscape. It seems open source has indeed won, but let&#8217;s not gloat; let&#8217;s make things even better. Open source has made many great changes to software possible, but the spirit of the founding community goes well beyond code.<span id="more-75505"></span></p>
                                   <h2>Pieces of a larger puzzle: Languages, libraries, and frameworks</h2>
                                   <p>Open source languages such as Java, Python, and Ruby benefit from communities that not only report problems, but add to the efficacy and optimization of a language. New libraries and frameworks augment these and other open source languages by giving software engineers tools to solve problems quickly. However, languages with their libraries and frameworks are but the tools to get things done. </p>
                                   <p>In the past, we&#8217;ve organized OSCON&#8217;s tracks by language. This reflected a time when software engineers tended to stick to one language or another. This was before the days of 782 JavaScript frameworks and several different languages, database choices, and architectures that are optimized for different types of projects. Well, no more! </p>
                                   <p>This year, you&#8217;ll see that we&#8217;ve set the tracks so they reflect what you, the software engineer, need to get things done.</p>
                                   <ul>
                                      <li> <strong>Protect</strong> &mdash; Identity, privacy, and security are emerging and nuanced facets in the digital age, and now they&#8217;re also an exciting cross-functional track at OSCON 2015.</li>
                                      <li> <strong>Scale</strong> &mdash; From compilation and interpreter time to DOM manipulation, browser responsiveness, and network latency, we&#8217;ll explore scale and performance in all their aspects.</li>
                                      <li> <strong>Mobility</strong> &mdash; We&#8217;ll look at what it means to have a successful mobile game plan, from wearables to native apps.</li>
                                      <li> <strong>Collaboration</strong> &mdash; Making projects work requires communication, collaboration, and respect. We&#8217;ll look at the ways a new generation of tools and approaches can help you work.</li>
                                      <li> <strong>Craft</strong> &mdash; You need to work on you! We&#8217;ll present ideas on how to fail fast, say �no,� overcome impostor syndrome, and integrate your work into the open source community.</li>
                                      <li> <strong>Architecture</strong> &mdash; Software architecture is a massive multidisciplinary subject, covering many roles and responsibilities &mdash; and it&#8217;s a key position in the success of any business.</li>
                                      <li> <strong>Design</strong> &mdash; It&#8217;s critical for success; learn how to incorporate design best practices from the beginning of your project and all the way through.</li>
                                      <li> <strong>Data</strong> &mdash; We&#8217;ll tackle data&#8217;s continued, growing influence over the entire business world and present ways you can make it work for you.</li>
                                      <li> <strong>Foundations</strong> &mdash; A strong foundation in computational thinking, problem solving, and programming best practices makes for a successful programmer.</li>
                                      <li> <strong>Solve</strong> &mdash; Harness the power of math to manipulate, secure, and create data.</li>
                                   </ul>
                                   <p>Over the next few weeks, look for posts that go into more depth about each of these tracks.</p>
                                   <h2>An evolved software community</h2>
                                   <p>As I mentioned in my recent post &#8220;<a href="http://radar.oreilly.com/2015/02/software-engineer-developer-coding-architecture-mobile-open-source.html">Software engineers must continuously learn and integrate</a>,&#8221; organizations need to integrate open source code and open source culture if they want to move at a speed that will drive success. This year, <a href="http://www.oscon.com/open-source-2015/public/schedule/topic/1405?intcmp=il-prog-confreg-update-os15_20150409_radar_oscon_15_announcement_cultivate">we&#8217;ll focus on company culture via Cultivate</a>, a two-day event at OSCON. We&#8217;ll discuss the values and practices that enable organizations to respond with agility to changes in their products and their customers. If you need to learn about growing teams within your organization, instilling leadership skills that are necessary for success now and in the future, and creating processes that work for rather than against you, you need to be at <a href="http://www.oscon.com/open-source-2015/public/schedule/topic/1405?intcmp=il-prog-confreg-update-os15_20150409_radar_oscon_15_announcement_cultivate">Cultivate</a>. </p>
                                   <h2>OSCON, reimagined</h2>
                                   <p>OSCON touches upon all aspects of the software industry and what we cover at O&#8217;Reilly. It&#8217;s been at the heart of what we do for almost 18 years. But going forward, we want to make sure we continue to meet the needs of our community while advancing the open source message. That&#8217;s why we&#8217;re taking a new approach with the event&#8217;s structure. </p>
                                   <p>Whether you&#8217;re attending OSCON for the first time or you&#8217;ve been to this conference many times before, you&#8217;re already a part of this thriving community. We look forward to showing you what we have in store as OSCON is expanded and reimagined.</p>
                                   <p style="margin-top: 25px; margin-bottom: 40px;"><a href="http://www.oscon.com/open-source-2015?intcmp=il-prog-confreg-update-os15_20150409_radar_oscon_15_announcement"><strong>Learn more about the OSCON 2015 program</strong></a>.</p>
                                   <!-- RSPEAK_STOP -->
                                   <div id=\'ctx-sl-subscribe\' class=\'ctx-subscribe-container ctx-clearfix\'></div>
                                   <div id=\'ctx-module\' class=\'ctx-module-container ctx-clearfix\'></div>
                                   Here is one entry content as the bottom of the Node.
                                </div>
                                <!-- .entry-content -->
                             </div>
                             <div class="entry-utility">
        tags: <a href=\'http://radar.oreilly.com/tag/be-a-software-engineer\' title=\'Be a Software Engineer Tag\' rel=\'tag\'>Be a Software Engineer</a>, <a href=\'http://radar.oreilly.com/tag/expanded-view-of-mobile-development-and-data\' title=\'Expanded View of Mobile Development and Data Tag\' rel=\'tag\'>Expanded View of Mobile Development and Data</a>, <a href=\'http://radar.oreilly.com/tag/integrate-open-source-culture-and-code\' title=\'Integrate Open Source Culture and Code Tag\' rel=\'tag\'>Integrate Open Source Culture and Code</a>, <a href=\'http://radar.oreilly.com/tag/languages\' title=\'languages Tag\' rel=\'tag\'>languages</a>, <a href=\'http://radar.oreilly.com/tag/learn-how-to-solve-problems\' title=\'Learn How to Solve Problems Tag\' rel=\'tag\'>Learn How to Solve Problems</a>, <a href=\'http://radar.oreilly.com/tag/open-source\' title=\'open source Tag\' rel=\'tag\'>open source</a>, <a href=\'http://radar.oreilly.com/tag/oscon\' title=\'OSCON Tag\' rel=\'tag\'>OSCON</a>, <a href=\'http://radar.oreilly.com/tag/oscon-2015\' title=\'OSCON 2015 Tag\' rel=\'tag\'>OSCON 2015</a>, <a href=\'http://radar.oreilly.com/tag/programming-2\' title=\'programming Tag\' rel=\'tag\'>programming</a>, <a href=\'http://radar.oreilly.com/tag/software-engineers\' title=\'software engineers Tag\' rel=\'tag\'>software engineers</a>
                             </div>
                             <!-- .entry-utility -->
                             <div class="entry-utility social">
                                <div class="et_social_inline et_social_mobile_on et_social_inline_bottom">
                                   <div class="et_social_networks et_social_autowidth et_social_slide et_social_rectangle et_social_left et_social_no_animation et_social_outer_dark">
                                      <ul class="et_social_icons_container">
                                         <li class="et_social_twitter"><a href="http://twitter.com/share?text=Open source won, so what&amp;#8217;s next?&#038;url=http%3A%2F%2Fradar.oreilly.com%2F2015%2F04%2Fopen-source-won-so-whats-next.html&#038;via=Radar" class="et_social_share" rel="nofollow" data-social_name="twitter" data-post_id="75505" data-social_type="share"><i class="et_social_icon et_social_icon_twitter"></i><span class="et_social_overlay"></span></a></li>
                                         <li class="et_social_googleplus"><a href="https://plus.google.com/share?url=http%3A%2F%2Fradar.oreilly.com%2F2015%2F04%2Fopen-source-won-so-whats-next.html&#038;t=Open source won, so what&amp;#8217;s next?" class="et_social_share" rel="nofollow" data-social_name="googleplus" data-post_id="75505" data-social_type="share"><i class="et_social_icon et_social_icon_googleplus"></i><span class="et_social_overlay"></span></a></li>
                                         <li class="et_social_facebook"><a href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fradar.oreilly.com%2F2015%2F04%2Fopen-source-won-so-whats-next.html&#038;t=Open source won, so what&amp;#8217;s next?" class="et_social_share" rel="nofollow" data-social_name="facebook" data-post_id="75505" data-social_type="share"><i class="et_social_icon et_social_icon_facebook"></i><span class="et_social_overlay"></span></a></li>
                                         <li class="et_social_linkedin"><a href="http://www.linkedin.com/shareArticle?mini=true&#038;url=http%3A%2F%2Fradar.oreilly.com%2F2015%2F04%2Fopen-source-won-so-whats-next.html&#038;title=Open source won, so what&amp;#8217;s next?" class="et_social_share" rel="nofollow" data-social_name="linkedin" data-post_id="75505" data-social_type="share"><i class="et_social_icon et_social_icon_linkedin"></i><span class="et_social_overlay"></span></a></li>
                                      </ul>
                                   </div>
                                </div>
                                <span class="comments-link utility-items"><a href="http://radar.oreilly.com/2015/04/open-source-won-so-whats-next.html#comments"><span class="dsq-postid" data-dsqidentifier="75505 http://radar.oreilly.com/?p=75505">Comment: 1</span></a></span>
                                <div class="clear"></div>
                             </div>
                             <!-- .entry-utility -->
                             <!-- AddThis Button END -->
                          </div>
                          <!-- #post-## -->
                          <div id="post-bottom-widget-area">
                             <div class="textwidget">
                                <!-- programming category newsletter  -->
                                <style type="text/css">
                                   #post-bottom-widget-area iframe { height:100px !important;}
                                   .single .hentry { padding-bottom:20px; }
                                   #content #newsletter { padding:0; margin:0; border-bottom:1px solid #888; }
                                   #content #newsletter > h3 { margin:0; padding:0; font-size:22px; line-height:1.1em; font-weight:bold; }
                                   #content #newsletter > h4 { margin:0 0 5px; padding:0; font-size:15px; line-height:1.3em; font-weight:normal; }
                                   #content #newsletter > p { margin:0 0 20px; padding:0; font-size:15px; line-height:1.3em; font-weight:normal; }
                                   @media only screen and (max-width: 950px) {
            #post-bottom-widget-area { margin:20px 0 !important;}
        }
                                </style>
                                <div id="newsletter">
                                   <h3>Get the O&rsquo;Reilly Programming Newsletter</h3>
                                   <p>Weekly insight from industry insiders. Plus exclusive content and offers.</p>
                                   <iframe src="//cdn.oreillystatic.com/oreilly/email/forms/email_signup_widget.html?site=radar&amp;topic=prog&amp;loc=botpost&amp;emtype=nl" id="newsletter-popup-frame" width="100%" height="100%"></iframe>
                                </div>
                                <div class="clear"></div>
                             </div>
                             <div class="clear"></div>
                          </div>
                          <!-- #post-bottom-widget -->
                          <a name="comments"></a>
                          <a name="respond"></a>
                          <div id="disqus_thread">
                             <div id="dsq-content">
                                <ul id="dsq-comments">
                                   <li class="comment even thread-even depth-1" id="dsq-comment-44935">
                                      <div id="dsq-comment-header-44935" class="dsq-comment-header">
                                         <cite id="dsq-cite-44935">
                                         <span id="dsq-author-user-44935">Brandon Allen</span>
                                         </cite>
                                      </div>
                                      <div id="dsq-comment-body-44935" class="dsq-comment-body">
                                         <div id="dsq-comment-message-44935" class="dsq-comment-message">
                                            <p>What I don&#8217;t understand is why it is being moved to Texas next year. 17 years of Portland and now you abandon us :(</p>
                                         </div>
                                      </div>
                                   </li>
                                   <!-- #comment-## -->
                                </ul>
                             </div>
                          </div>
                       </div>
                       <!-- #content -->
                       <div id="sidebar" class="widget-area">
                          <div id="text-67" class="widget-container widget_text">
                             <h3 class="widget-title">Get the Programming Newsletter</h3>
                             <div class="textwidget">
                                <div id="newsletter" class="widget-container">
                                   <!-- programming category newsletter -->
                                   <style type="text/css">
                                      #newsletter.widget-container iframe { height:65px !important;}
                                      #newsletter.widget-container > p { margin:0 0 10px; padding:0; }
                                      #newsletter.widget-container { margin-bottom: 5px; }
                                   </style>
                                   <p>Weekly insight from industry insiders. Plus exclusive content and offers.</p>
                                   <iframe src="//cdn.oreillystatic.com/oreilly/email/forms/email_signup_widget.html?site=radar&amp;topic=prog&amp;loc=rightrail&amp;emtype=nl" id="newsletter-popup-frame" width="100%" height="100%"></iframe>
                                   <div class="clear"></div>
                                </div>
                             </div>
                             <div class="clear"></div>
                          </div>
                          <div id="text-81" class="widget-container widget_text">
                             <h3 class="widget-title">Featured Download</h3>
                             <div class="textwidget">
                                <p align="center"><a href="http://www.oreilly.com/programming/free/engineering-managers-guide-design-patterns.csp" onClick="var s=s_gi(s_account); s.eVar23=\'radar-programming-rcolbot-free-0636920042099\'; s.events=\'event5\'; s.linkTrackVars=\'eVar23,events\'; s.linkTrackEvents=\'event5\'; s.tl(this,\'o\',\'Internal Ad Click\');" ><img src="http://covers.oreillystatic.com/images/0636920042099/cat.gif"></a><br /><a href="http://www.oreilly.com/programming/free/engineering-managers-guide-design-patterns.csp" onClick="var s=s_gi(s_account); s.eVar23=\'radar-programming-rcolbot-free-0636920042099\'; s.events=\'event5\'; s.linkTrackVars=\'eVar23,events\'; s.linkTrackEvents=\'event5\'; s.tl(this,\'o\',\'Internal Ad Click\');" >Download the free ebook ></a><br /><a href="http://www.oreilly.com/programming/free">More free reports ></a></p>
                             </div>
                             <div class="clear"></div>
                          </div>
                          <div id="recent-posts-6" class="widget-container widget_recent_entries">
                             <h3 class="widget-title">Recent Posts</h3>
                             <ul>
                                <li>
                                   <a href="http://radar.oreilly.com/2015/10/four-short-links-30-october-2015.html">Four short links: 30 October 2015</a>
                                </li>
                                <li>
                                   <a href="http://radar.oreilly.com/2015/10/do-one-thing.html">Do one thing&#8230;</a>
                                </li>
                                <li>
                                   <a href="http://radar.oreilly.com/2015/10/signals-from-the-2015-oreilly-velocity-conference-in-amsterdam.html">Signals from the 2015 O&#8217;Reilly Velocity Conference in Amsterdam</a>
                                </li>
                                <li>
                                   <a href="http://radar.oreilly.com/2015/10/the-first-rule-of-management-resist-the-urge-to-manage.html">The first rule of management: Resist the urge to manage</a>
                                </li>
                                <li>
                                   <a href="http://radar.oreilly.com/2015/10/adam-connor-on-culture-codes-of-conduct-and-critiques.html">Adam Connor on culture, codes of conduct, and critiques</a>
                                </li>
                             </ul>
                             <div class="clear"></div>
                          </div>
                          <div id="text-21" class="widget-container widget_text">
                             <h3 class="widget-title">Most Recently Discussed</h3>
                             <div class="textwidget">
                                <div id="recentcomments" class="dsq-widget">
                                   <script type="text/javascript" src="http://oreillyradar.disqus.com/recent_comments_widget.js?num_items=5&hide_avatars=1&avatar_size=32&excerpt_length=100"></script>
                                </div>
                             </div>
                             <div class="clear"></div>
                          </div>
                          <div  class="widget-container radar-archives">
                             <h3 class="widget-title">Archives</h3>
                             <ul>
                                <li>
                                   <select id="archive_id" name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
                                      <option value="">Archives by Month...</option>
                                      <option value=\'http://radar.oreilly.com/2015/10\'> October 2015 </option>
                                      <option value=\'http://radar.oreilly.com/2015/09\'> September 2015 </option>
                                      <option value=\'http://radar.oreilly.com/2015/08\'> August 2015 </option>
                                      <option value=\'http://radar.oreilly.com/2015/07\'> July 2015 </option>
                                      <option value=\'http://radar.oreilly.com/2015/06\'> June 2015 </option>
                                      <option value=\'http://radar.oreilly.com/2015/05\'> May 2015 </option>
                                      <option value=\'http://radar.oreilly.com/2015/04\'> April 2015 </option>
                                      <option value=\'http://radar.oreilly.com/2015/03\'> March 2015 </option>
                                      <option value=\'http://radar.oreilly.com/2015/02\'> February 2015 </option>
                                      <option value=\'http://radar.oreilly.com/2015/01\'> January 2015 </option>
                                      <option value=\'http://radar.oreilly.com/2014/12\'> December 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/11\'> November 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/10\'> October 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/09\'> September 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/08\'> August 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/07\'> July 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/06\'> June 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/05\'> May 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/04\'> April 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/03\'> March 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/02\'> February 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2014/01\'> January 2014 </option>
                                      <option value=\'http://radar.oreilly.com/2013/12\'> December 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/11\'> November 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/10\'> October 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/09\'> September 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/08\'> August 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/07\'> July 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/06\'> June 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/05\'> May 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/04\'> April 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/03\'> March 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/02\'> February 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2013/01\'> January 2013 </option>
                                      <option value=\'http://radar.oreilly.com/2012/12\'> December 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/11\'> November 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/10\'> October 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/09\'> September 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/08\'> August 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/07\'> July 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/06\'> June 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/05\'> May 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/04\'> April 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/03\'> March 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/02\'> February 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2012/01\'> January 2012 </option>
                                      <option value=\'http://radar.oreilly.com/2011/12\'> December 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/11\'> November 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/10\'> October 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/09\'> September 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/08\'> August 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/07\'> July 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/06\'> June 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/05\'> May 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/04\'> April 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/03\'> March 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/02\'> February 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2011/01\'> January 2011 </option>
                                      <option value=\'http://radar.oreilly.com/2010/12\'> December 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/11\'> November 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/10\'> October 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/09\'> September 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/08\'> August 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/07\'> July 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/06\'> June 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/05\'> May 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/04\'> April 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/03\'> March 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/02\'> February 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2010/01\'> January 2010 </option>
                                      <option value=\'http://radar.oreilly.com/2009/12\'> December 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/11\'> November 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/10\'> October 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/09\'> September 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/08\'> August 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/07\'> July 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/06\'> June 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/05\'> May 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/04\'> April 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/03\'> March 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/02\'> February 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2009/01\'> January 2009 </option>
                                      <option value=\'http://radar.oreilly.com/2008/12\'> December 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/11\'> November 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/10\'> October 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/09\'> September 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/08\'> August 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/07\'> July 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/06\'> June 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/05\'> May 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/04\'> April 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/03\'> March 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/02\'> February 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2008/01\'> January 2008 </option>
                                      <option value=\'http://radar.oreilly.com/2007/12\'> December 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/11\'> November 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/10\'> October 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/09\'> September 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/08\'> August 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/07\'> July 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/06\'> June 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/05\'> May 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/04\'> April 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/03\'> March 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/02\'> February 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2007/01\'> January 2007 </option>
                                      <option value=\'http://radar.oreilly.com/2006/12\'> December 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/11\'> November 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/10\'> October 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/09\'> September 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/08\'> August 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/07\'> July 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/06\'> June 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/05\'> May 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/04\'> April 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/03\'> March 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/02\'> February 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2006/01\'> January 2006 </option>
                                      <option value=\'http://radar.oreilly.com/2005/12\'> December 2005 </option>
                                      <option value=\'http://radar.oreilly.com/2005/11\'> November 2005 </option>
                                      <option value=\'http://radar.oreilly.com/2005/10\'> October 2005 </option>
                                      <option value=\'http://radar.oreilly.com/2005/09\'> September 2005 </option>
                                      <option value=\'http://radar.oreilly.com/2005/08\'> August 2005 </option>
                                      <option value=\'http://radar.oreilly.com/2005/07\'> July 2005 </option>
                                      <option value=\'http://radar.oreilly.com/2005/06\'> June 2005 </option>
                                      <option value=\'http://radar.oreilly.com/2005/05\'> May 2005 </option>
                                      <option value=\'http://radar.oreilly.com/2005/04\'> April 2005 </option>
                                      <option value=\'http://radar.oreilly.com/2005/03\'> March 2005 </option>
                                   </select>
                                </li>
                                <li>
                                   <form action="http://radar.oreilly.com/" method="get">
                                      <div>
                                         <select name=\'cat\' id=\'cat\' class=\'postform\'  onchange=\'return this.form.submit()\'>
                                            <option value=\'-1\'>Archives by Topic&#8230;</option>
                                            <option class="level-0" value="3">Data</option>
                                            <option class="level-0" value="5443">Design</option>
                                            <option class="level-0" value="5444">Emerging Tech</option>
                                            <option class="level-0" value="4871">IoT</option>
                                            <option class="level-0" value="9">Programming</option>
                                            <option class="level-0" value="12">Web Ops &amp; Performance</option>
                                            <option class="level-0" value="5355">Web Platform</option>
                                         </select>
                                         <noscript>
                                            <div><input type="submit" value="View" /></div>
                                         </noscript>
                                      </div>
                                   </form>
                                </li>
                                <li id="users">
                                   <form action="http://radar.oreilly.com/" method="get">
                                      <div>
                                         <select name=\'author\' id=\'author\' class=\'\' onchange=\'return this.form.submit()\'>
                                            <option value=\'-1\'>Archives by Author...</option>
                                            <option value=\'507\'>A. Sinan Unur</option>
                                            <option value=\'508\'>Aaron Sumner</option>
                                            <option value=\'738\'>Adam Connor</option>
                                            <option value=\'4\'>Adam DuVander</option>
                                            <option value=\'376\'>Adam Flaherty</option>
                                            <option value=\'3\'>Adam Messinger</option>
                                            <option value=\'5\'>Adam Witwer</option>
                                            <option value=\'643\'>Adrian Mendoza</option>
                                            <option value=\'741\'>Adrian Mouat</option>
                                            <option value=\'686\'>Akmal Chaudhri</option>
                                            <option value=\'2\'>Alasdair Allan</option>
                                            <option value=\'588\'>Alex Bordei</option>
                                            <option value=\'7\'>Alex Bowyer</option>
                                            <option value=\'9\'>Alex Iskold</option>
                                            <option value=\'6\'>Alexander Macgillivray</option>
                                            <option value=\'715\'>Alice Boxhall</option>
                                            <option value=\'607\'>Alice Zheng</option>
                                            <option value=\'10\'>Alistair Croll</option>
                                            <option value=\'471\'>Allen Downey</option>
                                            <option value=\'11\'>Allen Noren</option>
                                            <option value=\'12\'>Allison Randal</option>
                                            <option value=\'482\'>Ally MacDonald</option>
                                            <option value=\'477\'>Alois Reitbauer</option>
                                            <option value=\'347\'>Alysa Hutnik</option>
                                            <option value=\'605\'>Amelia Bellamy-Royds</option>
                                            <option value=\'625\'>Amr Awadallah</option>
                                            <option value=\'358\'>Amy Heineike</option>
                                            <option value=\'467\'>Amy Jollymore</option>
                                            <option value=\'426\'>Amy Unruh</option>
                                            <option value=\'13\'>Anant Jhingran</option>
                                            <option value=\'414\'>Andreas Antonopoulos</option>
                                            <option value=\'501\'>Andrew Collette</option>
                                            <option value=\'15\'>Andrew Odewahn</option>
                                            <option value=\'14\'>Andrew Savikas</option>
                                            <option value=\'16\'>Andrew Shafer</option>
                                            <option value=\'619\'>Andrew T Baker</option>
                                            <option value=\'510\'>Andy Fitzgerald</option>
                                            <option value=\'18\'>Andy Kirk</option>
                                            <option value=\'355\'>Andy Konwinski</option>
                                            <option value=\'19\'>Andy Oram</option>
                                            <option value=\'656\'>Angela Rufino</option>
                                            <option value=\'309\'>Ann Spencer</option>
                                            <option value=\'262\'>Ann Waldo</option>
                                            <option value=\'359\'>Anna Smith</option>
                                            <option value=\'562\'>Anne Gentle</option>
                                            <option value=\'574\'>Anni Ylagan</option>
                                            <option value=\'21\'>Ari Gesher</option>
                                            <option value=\'20\'>Aria Haghighi</option>
                                            <option value=\'410\'>Ariya Hidayat</option>
                                            <option value=\'664\'>Arnold Robbins</option>
                                            <option value=\'22\'>Artur Bergman</option>
                                            <option value=\'658\'>Arun Gupta</option>
                                            <option value=\'23\'>Audrey Watters</option>
                                            <option value=\'293\'>Avi Bryant</option>
                                            <option value=\'571\'>Barb Edson</option>
                                            <option value=\'421\'>Barbara Bermes</option>
                                            <option value=\'486\'>Baron Schwartz</option>
                                            <option value=\'26\'>Barry Devlin</option>
                                            <option value=\'624\'>Barry O&#039;Reilly</option>
                                            <option value=\'390\'>Beau Cronin</option>
                                            <option value=\'419\'>Ben Christensen</option>
                                            <option value=\'587\'>Ben Evans</option>
                                            <option value=\'665\'>Ben Henick</option>
                                            <option value=\'27\'>Ben Lorica</option>
                                            <option value=\'610\'>Benjamin Hindman</option>
                                            <option value=\'684\'>Beth Massi</option>
                                            <option value=\'462\'>Bill Higgins</option>
                                            <option value=\'591\'>Bill Lubanovic</option>
                                            <option value=\'28\'>Bill McCoy</option>
                                            <option value=\'461\'>Bonnie Feldman</option>
                                            <option value=\'30\'>Bradley Voytek</option>
                                            <option value=\'31\'>Brady Forrest</option>
                                            <option value=\'539\'>Brandon Satrom</option>
                                            <option value=\'32\'>Brett McLaughlin</option>
                                            <option value=\'33\'>Brett Sandusky</option>
                                            <option value=\'34\'>Brett Sheppard</option>
                                            <option value=\'25\'>Brian Ahier</option>
                                            <option value=\'480\'>Brian Anderson</option>
                                            <option value=\'35\'>Brian Boyer</option>
                                            <option value=\'533\'>brian d foy</option>
                                            <option value=\'494\'>Brian d&#039;Alessandro</option>
                                            <option value=\'648\'>Brian Foster</option>
                                            <option value=\'244\'>Brian Jepson</option>
                                            <option value=\'646\'>Brian Kardell</option>
                                            <option value=\'481\'>Brian MacDonald</option>
                                            <option value=\'36\'>Brian O&#039;Leary</option>
                                            <option value=\'677\'>Brian Rinaldi</option>
                                            <option value=\'38\'>Brian Sawyer</option>
                                            <option value=\'672\'>Brigitte Piniewski</option>
                                            <option value=\'37\'>Bruce Stewart</option>
                                            <option value=\'666\'>Carin Meier</option>
                                            <option value=\'39\'>Carl Hewitt</option>
                                            <option value=\'40\'>Carl Malamud</option>
                                            <option value=\'717\'>Casey West</option>
                                            <option value=\'328\'>Cathy O&#039;Neil</option>
                                            <option value=\'483\'>Chao (Ray) Feng</option>
                                            <option value=\'448\'>Chiu-ki Chan</option>
                                            <option value=\'516\'>Chris Cornutt</option>
                                            <option value=\'41\'>Chris Meade</option>
                                            <option value=\'284\'>Chris Vander Mey</option>
                                            <option value=\'300\'>Chris Wiggins</option>
                                            <option value=\'44\'>Christine Perey</option>
                                            <option value=\'42\'>Ciara Byrne</option>
                                            <option value=\'729\'>Claire Rowland</option>
                                            <option value=\'43\'>Cliff Miller</option>
                                            <option value=\'479\'>Colt McAnlis</option>
                                            <option value=\'634\'>Cornelia L�vy-Bencheton</option>
                                            <option value=\'640\'>Cory Doctorow</option>
                                            <option value=\'306\'>Courtney Nash</option>
                                            <option value=\'707\'>Courtney Webster</option>
                                            <option value=\'45\'>Dale Dougherty</option>
                                            <option value=\'422\'>Dan Saffer</option>
                                            <option value=\'577\'>Danese Cooper</option>
                                            <option value=\'46\'>Darren Barefoot</option>
                                            <option value=\'430\'>Dave Himrod</option>
                                            <option value=\'47\'>Dave McClure</option>
                                            <option value=\'556\'>Dave Zwieback</option>
                                            <option value=\'663\'>David Beyer</option>
                                            <option value=\'726\'>David Blaikie</option>
                                            <option value=\'653\'>David Cranor</option>
                                            <option value=\'435\'>David Elfi</option>
                                            <option value=\'49\'>David Leinweber</option>
                                            <option value=\'702\'>David Mertz</option>
                                            <option value=\'50\'>David Recordon</option>
                                            <option value=\'48\'>David Sims</option>
                                            <option value=\'559\'>David Stephenson</option>
                                            <option value=\'51\'>DC Denison</option>
                                            <option value=\'242\'>Deni Auclair</option>
                                            <option value=\'313\'>Derek Jacoby</option>
                                            <option value=\'660\'>Dinesh Subhraveti</option>
                                            <option value=\'427\'>Dino Esposito</option>
                                            <option value=\'714\'>Dirk Slama</option>
                                            <option value=\'53\'>DJ Patil</option>
                                            <option value=\'744\'>Dominique Guinard</option>
                                            <option value=\'416\'>Doug Finke</option>
                                            <option value=\'52\'>Doug Hill</option>
                                            <option value=\'695\'>Doug Sillars</option>
                                            <option value=\'512\'>Dr. Venkat Subramaniam</option>
                                            <option value=\'575\'>Drew Dara-Abrams</option>
                                            <option value=\'698\'>Duncan DeVore</option>
                                            <option value=\'348\'>Duncan Ross</option>
                                            <option value=\'491\'>Dusty Phillips</option>
                                            <option value=\'569\'>DW Wheeler</option>
                                            <option value=\'54\'>Dylan Field</option>
                                            <option value=\'55\'>E.A. Vander Veer</option>
                                            <option value=\'56\'>Edd Dumbill</option>
                                            <option value=\'236\'>Edie Freedman</option>
                                            <option value=\'465\'>Eli Goodman</option>
                                            <option value=\'392\'>Elisabeth Robson</option>
                                            <option value=\'58\'>Elizabeth Corcoran</option>
                                            <option value=\'544\'>Ellen Friedman</option>
                                            <option value=\'570\'>Elliott Hauser</option>
                                            <option value=\'514\'>Elliotte Rusty Harold</option>
                                            <option value=\'434\'>Emma Jane Westby</option>
                                            <option value=\'59\'>Eoin Purcell</option>
                                            <option value=\'393\'>Eric Freeman</option>
                                            <option value=\'682\'>Eric Lippert</option>
                                            <option value=\'451\'>Eric Redmond</option>
                                            <option value=\'60\'>Eric Ries</option>
                                            <option value=\'731\'>Evangelos Simoudis</option>
                                            <option value=\'500\'>Ezra Haber Glenn</option>
                                            <option value=\'541\'>Faye Williams</option>
                                            <option value=\'578\'>Federico Castanedo</option>
                                            <option value=\'503\'>Federico Lucifredi</option>
                                            <option value=\'62\'>Fred Trotter</option>
                                            <option value=\'515\'>Fred van den Bosch</option>
                                            <option value=\'68\'>Gabe Zichermann</option>
                                            <option value=\'63\'>Gavin Starks</option>
                                            <option value=\'463\'>George Reese</option>
                                            <option value=\'705\'>Gerhard Kress</option>
                                            <option value=\'627\'>Gilad Rosner</option>
                                            <option value=\'519\'>Glen Martin</option>
                                            <option value=\'735\'>Greg Brail</option>
                                            <option value=\'67\'>Greg Whisenant</option>
                                            <option value=\'680\'>Gretchen Anderson</option>
                                            <option value=\'66\'>Gretchen Giles</option>
                                            <option value=\'436\'>Gustavo Franco</option>
                                            <option value=\'597\'>Gwen Shapira</option>
                                            <option value=\'581\'>Hadley Wickham</option>
                                            <option value=\'675\'>Hagen Finley</option>
                                            <option value=\'513\'>Hari K Gottipati</option>
                                            <option value=\'69\'>Heather McCormack</option>
                                            <option value=\'721\'>Heather Vescent</option>
                                            <option value=\'670\'>Helen Papagiannis</option>
                                            <option value=\'511\'>Hew Wolff</option>
                                            <option value=\'70\'>Howard Wen</option>
                                            <option value=\'71\'>Hugh McGuire</option>
                                            <option value=\'668\'>Ilya Grigorik</option>
                                            <option value=\'72\'>Imran Ali</option>
                                            <option value=\'470\'>J. Paul Reed</option>
                                            <option value=\'700\'>James Bond</option>
                                            <option value=\'73\'>James Bridle</option>
                                            <option value=\'74\'>James Turner</option>
                                            <option value=\'338\'>Janaya Williams</option>
                                            <option value=\'558\'>Jane Sarasohn-Kahn</option>
                                            <option value=\'728\'>Janine Barlow</option>
                                            <option value=\'75\'>Jason Grigsby</option>
                                            <option value=\'596\'>Jason Strimpel</option>
                                            <option value=\'573\'>Jay Kreps</option>
                                            <option value=\'529\'>Jay McGavren</option>
                                            <option value=\'350\'>Jayant Shekar</option>
                                            <option value=\'77\'>Jeevan Padiyar</option>
                                            <option value=\'719\'>Jeff Bollinger</option>
                                            <option value=\'429\'>Jeff Gothelf</option>
                                            <option value=\'375\'>Jeff Needham</option>
                                            <option value=\'712\'>Jeff Sussna</option>
                                            <option value=\'78\'>Jeffrey Carr</option>
                                            <option value=\'530\'>Jeffrey Carr</option>
                                            <option value=\'80\'>Jenn Webb</option>
                                            <option value=\'81\'>Jennifer Pahlka</option>
                                            <option value=\'730\'>Jenny Cheng</option>
                                            <option value=\'583\'>Jeremy Freeman</option>
                                            <option value=\'82\'>Jeremy Howard</option>
                                            <option value=\'679\'>Jeroen Janssens</option>
                                            <option value=\'685\'>Jerry Overton</option>
                                            <option value=\'83\'>Jesper Andersen</option>
                                            <option value=\'438\'>Jesse Anderson</option>
                                            <option value=\'84\'>Jesse Robbins</option>
                                            <option value=\'391\'>Jessica McKellar</option>
                                            <option value=\'594\'>Jesus M. Gonzalez-Barahona</option>
                                            <option value=\'534\'>Jez Humble</option>
                                            <option value=\'733\'>Jim Bird</option>
                                            <option value=\'636\'>Jim Scott</option>
                                            <option value=\'88\'>Jim Stogdill</option>
                                            <option value=\'87\'>Jimmy Guterman</option>
                                            <option value=\'342\'>Jo Prichard</option>
                                            <option value=\'638\'>Joanne Molesky</option>
                                            <option value=\'89\'>Jodee Rich</option>
                                            <option value=\'340\'>Joe Procopio</option>
                                            <option value=\'413\'>Johan Bergstr�m</option>
                                            <option value=\'673\'>John Adams</option>
                                            <option value=\'92\'>John Allspaw</option>
                                            <option value=\'93\'>John Battelle</option>
                                            <option value=\'457\'>John Boxall</option>
                                            <option value=\'743\'>John Cumbers</option>
                                            <option value=\'349\'>John Feland</option>
                                            <option value=\'351\'>John Foreman</option>
                                            <option value=\'86\'>John Geraci</option>
                                            <option value=\'85\'>John Graham-Cumming</option>
                                            <option value=\'678\'>John Jay Hilfiger</option>
                                            <option value=\'611\'>John King</option>
                                            <option value=\'94\'>John Labovitz</option>
                                            <option value=\'564\'>John Lindquist</option>
                                            <option value=\'495\'>John Myles White</option>
                                            <option value=\'628\'>John Piekos</option>
                                            <option value=\'635\'>John Russell</option>
                                            <option value=\'95\'>John Warren</option>
                                            <option value=\'561\'>John Wilbanks</option>
                                            <option value=\'255\'>Jon Bruner</option>
                                            <option value=\'498\'>Jon Callas</option>
                                            <option value=\'555\'>Jon Cowie</option>
                                            <option value=\'444\'>Jon Roberts</option>
                                            <option value=\'101\'>Jon Spinney</option>
                                            <option value=\'98\'>Jon Udell</option>
                                            <option value=\'585\'>Jonas Luster</option>
                                            <option value=\'96\'>Jonathan Alexander</option>
                                            <option value=\'177\'>Jonathan Reichental, Ph.D.</option>
                                            <option value=\'704\'>Jonathan Shariat</option>
                                            <option value=\'499\'>Jonathon Thurman</option>
                                            <option value=\'97\'>Jono Bacon</option>
                                            <option value=\'727\'>Jorge Arango</option>
                                            <option value=\'90\'>Joseph Hellerstein</option>
                                            <option value=\'99\'>Joseph J. Esposito</option>
                                            <option value=\'546\'>Josh Lockhart</option>
                                            <option value=\'509\'>Josh Simmons</option>
                                            <option value=\'683\'>Josha Stella</option>
                                            <option value=\'716\'>Joshua Backfield</option>
                                            <option value=\'100\'>Joshua-Mich�le Ross</option>
                                            <option value=\'464\'>Joy Beatty</option>
                                            <option value=\'103\'>Jud Valeski</option>
                                            <option value=\'104\'>Julie Steele</option>
                                            <option value=\'651\'>Justin Dombrowski</option>
                                            <option value=\'106\'>Justin Hall</option>
                                            <option value=\'107\'>Justo Hidalgo</option>
                                            <option value=\'117\'>Karl Fogel</option>
                                            <option value=\'108\'>Kassia Krozser</option>
                                            <option value=\'112\'>Kat Meyer</option>
                                            <option value=\'109\'>Kate Eltham</option>
                                            <option value=\'110\'>Kate Pullinger</option>
                                            <option value=\'453\'>Kathryn Barrett</option>
                                            <option value=\'645\'>Kathy Sierra</option>
                                            <option value=\'111\'>Kathy Walrath</option>
                                            <option value=\'458\'>Katie Cunningham</option>
                                            <option value=\'557\'>Katie Miller</option>
                                            <option value=\'525\'>Keith Comito</option>
                                            <option value=\'113\'>Keith Fahlgren</option>
                                            <option value=\'740\'>Kelsey Hightower</option>
                                            <option value=\'114\'>Ken Yarmosh</option>
                                            <option value=\'690\'>Kevin Czinger</option>
                                            <option value=\'115\'>Kevin Shockey</option>
                                            <option value=\'647\'>Kevin Sitto</option>
                                            <option value=\'116\'>Kevin Smokler</option>
                                            <option value=\'454\'>Khaled El Emam</option>
                                            <option value=\'621\'>Kieren James-Lubin</option>
                                            <option value=\'424\'>Kipp Bradford</option>
                                            <option value=\'469\'>Kit Seeborg</option>
                                            <option value=\'655\'>Kiyoto Tamura</option>
                                            <option value=\'492\'>kmatsudaira</option>
                                            <option value=\'118\'>Kurt Cagle</option>
                                            <option value=\'724\'>Kyle Dent</option>
                                            <option value=\'523\'>Lara Swanson</option>
                                            <option value=\'121\'>Laura Dawson</option>
                                            <option value=\'412\'>Laura Klein</option>
                                            <option value=\'339\'>Laurel Ruma</option>
                                            <option value=\'122\'>Laurie Petrycki</option>
                                            <option value=\'667\'>Leah Hunter</option>
                                            <option value=\'123\'>Leigh Dodds</option>
                                            <option value=\'125\'>Liliana Bounegru</option>
                                            <option value=\'126\'>Linda Stone</option>
                                            <option value=\'446\'>Lisa Mann</option>
                                            <option value=\'127\'>Liza Daly</option>
                                            <option value=\'725\'>Liza Kindred</option>
                                            <option value=\'626\'>Lorna Jane Mitchell</option>
                                            <option value=\'623\'>Lorne Lantz</option>
                                            <option value=\'593\'>Luciano Ramalho</option>
                                            <option value=\'128\'>Lucy Gray</option>
                                            <option value=\'129\'>Lukas Biewald</option>
                                            <option value=\'151\'>Mac Slocum</option>
                                            <option value=\'130\'>Madhusudhan Konda</option>
                                            <option value=\'396\'>Mandi Walls</option>
                                            <option value=\'489\'>Manish Lachwani</option>
                                            <option value=\'132\'>Marc Goodman</option>
                                            <option value=\'131\'>Marc Hedlund</option>
                                            <option value=\'720\'>Marcus Carr</option>
                                            <option value=\'616\'>Marie Beaugureau</option>
                                            <option value=\'133\'>Marie Bjerede</option>
                                            <option value=\'134\'>Mark Drapeau</option>
                                            <option value=\'447\'>Mark Grover</option>
                                            <option value=\'652\'>Mark Jeftovic</option>
                                            <option value=\'688\'>Mark Lustig</option>
                                            <option value=\'431\'>Mark Lutz</option>
                                            <option value=\'135\'>Mark Nelson</option>
                                            <option value=\'554\'>Mark Pacelle</option>
                                            <option value=\'150\'>Mark Sigal</option>
                                            <option value=\'676\'>Mark Zeman</option>
                                            <option value=\'136\'>Marko Gargenta</option>
                                            <option value=\'718\'>Markus Eisele</option>
                                            <option value=\'612\'>Martin Kalin</option>
                                            <option value=\'613\'>Martin Kleppmann</option>
                                            <option value=\'291\'>Mary Treseler</option>
                                            <option value=\'142\'>Matt Garrish</option>
                                            <option value=\'567\'>Matt Makai</option>
                                            <option value=\'473\'>Matt Neuburg</option>
                                            <option value=\'140\'>Matt Wood</option>
                                            <option value=\'137\'>Matthew Burton</option>
                                            <option value=\'460\'>Matthew Gast</option>
                                            <option value=\'408\'>Matthew McCullough</option>
                                            <option value=\'138\'>Matthew Russell</option>
                                            <option value=\'468\'>Matthew Russell</option>
                                            <option value=\'387\'>Max Kanat-Alexander</option>
                                            <option value=\'669\'>Max Meyers</option>
                                            <option value=\'694\'>Max Neunh�ffer</option>
                                            <option value=\'388\'>Max Shron</option>
                                            <option value=\'732\'>Maxime Najim</option>
                                            <option value=\'637\'>Meghan Athavale</option>
                                            <option value=\'289\'>Meghan Blanchette</option>
                                            <option value=\'579\'>Mehdi Daoudi</option>
                                            <option value=\'687\'>Melissa DiEgidio</option>
                                            <option value=\'681\'>Micah Godbolt</option>
                                            <option value=\'445\'>Michael DeHaan</option>
                                            <option value=\'144\'>Michael Driscoll</option>
                                            <option value=\'145\'>Michael Ferrari</option>
                                            <option value=\'734\'>Michael Fitzgerald</option>
                                            <option value=\'650\'>Michael Freeman</option>
                                            <option value=\'456\'>Michael Gold</option>
                                            <option value=\'450\'>Michael Hunger</option>
                                            <option value=\'146\'>Michael Jon Jensen</option>
                                            <option value=\'723\'>Michael Li</option>
                                            <option value=\'476\'>Michael Lopp</option>
                                            <option value=\'532\'>Michael McMillan</option>
                                            <option value=\'537\'>Michael Scroggins</option>
                                            <option value=\'406\'>Mike Amundsen</option>
                                            <option value=\'418\'>Mike Barlow</option>
                                            <option value=\'147\'>Mike Hendrickson</option>
                                            <option value=\'143\'>Mike Honda</option>
                                            <option value=\'148\'>Mike Loukides</option>
                                            <option value=\'487\'>Mike Petrovich</option>
                                            <option value=\'149\'>Mike Shatzkin</option>
                                            <option value=\'493\'>Mitchell Hashimoto</option>
                                            <option value=\'352\'>Naomi Robbins</option>
                                            <option value=\'152\'>Nat Torkington</option>
                                            <option value=\'214\'>Nate Osit</option>
                                            <option value=\'308\'>Nathan Jepson</option>
                                            <option value=\'630\'>Neal Ford</option>
                                            <option value=\'654\'>Nicholas Tollervey</option>
                                            <option value=\'155\'>Nick Bilton</option>
                                            <option value=\'156\'>Nick Farina</option>
                                            <option value=\'420\'>Nick Kolegraff</option>
                                            <option value=\'603\'>Nick Lombardi</option>
                                            <option value=\'157\'>Nick Ruffilo</option>
                                            <option value=\'341\'>Nicolas Garcia Belmonte</option>
                                            <option value=\'697\'>Nikhil Buduma</option>
                                            <option value=\'158\'>Nikolaj Nyholm</option>
                                            <option value=\'703\'>Nina DiPrimio</option>
                                            <option value=\'159\'>O&#039;Reilly Radar</option>
                                            <option value=\'354\'>O&#039;Reilly Strata</option>
                                            <option value=\'497\'>Ohad Samet</option>
                                            <option value=\'160\'>Osman Rashid</option>
                                            <option value=\'161\'>Pablo Francisco Arrieta Gomez</option>
                                            <option value=\'518\'>Paco Nathan</option>
                                            <option value=\'693\'>Pamela Pavliscak</option>
                                            <option value=\'162\'>Pamela Samuelson</option>
                                            <option value=\'589\'>Paris Buttfield-Addison</option>
                                            <option value=\'549\'>Patrick Mulder</option>
                                            <option value=\'553\'>Patrick Reynolds</option>
                                            <option value=\'671\'>Paul Kedrosky</option>
                                            <option value=\'163\'>Paul Spinrad</option>
                                            <option value=\'524\'>Pete Hodgson</option>
                                            <option value=\'167\'>Pete Warden</option>
                                            <option value=\'615\'>Peter Arijs</option>
                                            <option value=\'166\'>Peter Bennett</option>
                                            <option value=\'307\'>Peter Cooper</option>
                                            <option value=\'371\'>Peter Krautzberger</option>
                                            <option value=\'294\'>Peter Laflin</option>
                                            <option value=\'590\'>Peter Lewis</option>
                                            <option value=\'164\'>Peter Meyers</option>
                                            <option value=\'521\'>Philip Guo</option>
                                            <option value=\'455\'>Philipp Janert</option>
                                            <option value=\'404\'>Q Ethan McCallum</option>
                                            <option value=\'169\'>Quinn Norton</option>
                                            <option value=\'172\'>Rachel Roumeliotis</option>
                                            <option value=\'706\'>Rachel Wolfson</option>
                                            <option value=\'173\'>Rael Dornfest</option>
                                            <option value=\'657\'>Raffael Marty</option>
                                            <option value=\'522\'>Rajat Bhargava</option>
                                            <option value=\'174\'>Ramez Naam</option>
                                            <option value=\'175\'>Randy Bias</option>
                                            <option value=\'176\'>Raven Zachary</option>
                                            <option value=\'443\'>Ray DiGiacomo, Jr.</option>
                                            <option value=\'263\'>Renee DiResta</option>
                                            <option value=\'639\'>Reynold Xin</option>
                                            <option value=\'485\'>Richard Cook</option>
                                            <option value=\'439\'>Richard Dallaway</option>
                                            <option value=\'423\'>Richard Reese</option>
                                            <option value=\'552\'>Richard Warburton</option>
                                            <option value=\'183\'>Rob Tucker</option>
                                            <option value=\'179\'>Robbie Allen</option>
                                            <option value=\'182\'>Robert Kaye</option>
                                            <option value=\'181\'>Robert Passarella</option>
                                            <option value=\'180\'>Roberta Cairney</option>
                                            <option value=\'548\'>Roger Chen</option>
                                            <option value=\'184\'>Roger Magoulas</option>
                                            <option value=\'662\'>Rogier DocWilco Mulhuijzen</option>
                                            <option value=\'287\'>Ron Miller</option>
                                            <option value=\'178\'>Roseanne Fallin</option>
                                            <option value=\'644\'>Rune Madsen</option>
                                            <option value=\'661\'>Russell J.T. Dyer</option>
                                            <option value=\'691\'>Russell Jurney</option>
                                            <option value=\'484\'>Ryan Bethencourt</option>
                                            <option value=\'551\'>Ryan Neufeld</option>
                                            <option value=\'185\'>Ryan Stewart</option>
                                            <option value=\'568\'>Sam Newman</option>
                                            <option value=\'540\'>Samuel Mullen</option>
                                            <option value=\'186\'>Sanders Kleinfeld</option>
                                            <option value=\'191\'>Sara Peyton</option>
                                            <option value=\'187\'>Sara Winge</option>
                                            <option value=\'189\'>Sarah Milstein</option>
                                            <option value=\'190\'>Sarah Novotny</option>
                                            <option value=\'531\'>Scott Jenson</option>
                                            <option value=\'566\'>Scott Murray</option>
                                            <option value=\'563\'>Scott Rich</option>
                                            <option value=\'192\'>Scott Ruthfield</option>
                                            <option value=\'449\'>Sean McGregor</option>
                                            <option value=\'649\'>Sean O Sullivan</option>
                                            <option value=\'699\'>Sebastien Goasguen</option>
                                            <option value=\'194\'>S�bastien Pierre</option>
                                            <option value=\'547\'>Semmy Purewal</option>
                                            <option value=\'409\'>Seth Ladd</option>
                                            <option value=\'195\'>Shahid Shah</option>
                                            <option value=\'617\'>Shahin Farshchi</option>
                                            <option value=\'504\'>Shai Almog</option>
                                            <option value=\'633\'>Shannon Cutt</option>
                                            <option value=\'586\'>Shyam Seshadri</option>
                                            <option value=\'196\'>Silona Bonewald</option>
                                            <option value=\'576\'>Simon Chan</option>
                                            <option value=\'215\'>Simon Phipps</option>
                                            <option value=\'198\'>Simon St. Laurent</option>
                                            <option value=\'197\'>Simon Wardley</option>
                                            <option value=\'632\'>Spencer Critchley</option>
                                            <option value=\'659\'>Stefan Thies</option>
                                            <option value=\'620\'>Stephen Elston</option>
                                            <option value=\'363\'>Stephen O&#039;Grady</option>
                                            <option value=\'475\'>Stephen O&#039;Grady</option>
                                            <option value=\'199\'>Steve Souders</option>
                                            <option value=\'560\'>Steven Citron-Pousty</option>
                                            <option value=\'602\'>Steven Shorrock</option>
                                            <option value=\'417\'>Stoyan Stefanov</option>
                                            <option value=\'674\'>Susan Conant</option>
                                            <option value=\'200\'>Suzanne Axtell</option>
                                            <option value=\'202\'>Tara Hunt</option>
                                            <option value=\'572\'>Terrence Dorsey</option>
                                            <option value=\'203\'>Terry Jones</option>
                                            <option value=\'201\'>Tim Anderson</option>
                                            <option value=\'606\'>Tim Busbice</option>
                                            <option value=\'459\'>Tim Darling</option>
                                            <option value=\'204\'>Tim O&#039;Reilly</option>
                                            <option value=\'205\'>Timothy M. O&#039;Brien</option>
                                            <option value=\'622\'>Timothy McGovern</option>
                                            <option value=\'206\'>Tish Shute</option>
                                            <option value=\'526\'>Toby Inkster</option>
                                            <option value=\'207\'>Todd Sattersten</option>
                                            <option value=\'432\'>Tom Eisenmann</option>
                                            <option value=\'739\'>Tom Greever</option>
                                            <option value=\'692\'>Tom Pincince</option>
                                            <option value=\'208\'>Tom Steinberg</option>
                                            <option value=\'209\'>Tony Quartarolo</option>
                                            <option value=\'689\'>Tony Shakib</option>
                                            <option value=\'502\'>Trisha Gee</option>
                                            <option value=\'210\'>Troy Topnik</option>
                                            <option value=\'710\'>Tyler Akidau</option>
                                            <option value=\'211\'>Tyler Bell</option>
                                            <option value=\'538\'>Valeri Karpov</option>
                                            <option value=\'474\'>Vandad Nahvandipoor</option>
                                            <option value=\'212\'>Vanessa Fox</option>
                                            <option value=\'535\'>Varun Nagaraj</option>
                                            <option value=\'696\'>Vasant Dhar</option>
                                            <option value=\'346\'>Will Cukierski</option>
                                            <option value=\'618\'>William Mougayar</option>
                                            <option value=\'580\'>William O&#039;Connor</option>
                                            <option value=\'701\'>Yanpei Chen</option>
                                            <option value=\'401\'>Zigurd Mednieks</option>
                                         </select>
                                         <noscript>
                                            <div><input type="submit" value="View" /></div>
                                         </noscript>
                                      </div>
                                   </form>
                                </li>
                             </ul>
                             <div class="clear"></div>
                          </div>
                          <div id="text-36" class="widget-container widget_text">
                             <h3 class="widget-title">CONTACT US</h3>
                             <div class="textwidget">
                                <p style=" font-size: 12px;  line-height: 1.3; font-weight: bold;">Radar managing editor <br />
                                   <a href="mailto:contactradar@oreilly.com?subject=Contact Radar">Jenn Webb</a>
                                </p>
                             </div>
                             <div class="clear"></div>
                          </div>
                          <div class="clear"></div>
                       </div>
                    </div>
                    <!-- #container -->
                 </div>
                 <!-- #main -->
                 <div id="footer" role="contentinfo">
                    <div id="colophon">
                       <div id="footer-top">
                          <div class="menu-social-btm">
                             <ul id="menu-social-btn-bottom" class="menu">
                                <li id="menu-item-48494" class="social twitter menu-item menu-item-type-custom menu-item-object-custom menu-item-48494"><a href="http://twitter.com/oreillymedia">Twitter</a></li>
                                <li id="menu-item-48491" class="social youtube menu-item menu-item-type-custom menu-item-object-custom menu-item-48491"><a href="http://youtube.com/oreillymedia">YouTube</a></li>
                                <li id="menu-item-48492" class="social slideshare menu-item menu-item-type-custom menu-item-object-custom menu-item-48492"><a href="http://slideshare.net/oreillymedia">Slideshare</a></li>
                                <li id="menu-item-48493" class="social facebook menu-item menu-item-type-custom menu-item-object-custom menu-item-48493"><a href="https://www.facebook.com/OReillyRadar">Facebook</a></li>
                                <li id="menu-item-48495" class="social googleplus menu-item menu-item-type-custom menu-item-object-custom menu-item-48495"><a href="https://plus.google.com/105451978536505503907">Google+</a></li>
                                <li id="menu-item-48496" class="social rss menu-item menu-item-type-custom menu-item-object-custom menu-item-48496"><a href="http://feeds.feedburner.com/oreilly/news">RSS</a></li>
                                <li id="menu-item-48497" class="all-rss menu-item menu-item-type-custom menu-item-object-custom menu-item-48497"><a href="http://oreilly.com/feeds">View All RSS Feeds ></a></li>
                             </ul>
                          </div>
                          <div class="clear"></div>
                       </div>
                       <div class="execphpwidget">
                          <div id="multiColumnFooter">
                             <div id="footer-branding">
                                <p class="copyright">&copy; 2015, O\'Reilly Media, Inc.</p>
                                <p><span class="phone-number">(707) 827-7019</span><span class="phone-number">(800) 889-8969</span></p>
                                <p class="trademarks">All trademarks and registered trademarks appearing on oreilly.com are the property of their respective owners.</p>
                             </div>
                             <div class="contentSectionBlock">
                                <!-- style="width:25%;" -->
                                <div class="contentSectionContainer">
                                   <h3>About O\'Reilly</h3>
                                   <div class="menu-about-oreilly-container">
                                      <ul id="menu-about-oreilly" class="menu">
                                         <li id="menu-item-50110" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-50110"><a href="http://radar.oreilly.com/about/">About O&#8217;Reilly Radar</a></li>
                                         <li id="menu-item-67911" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-67911"><a href="http://radar.oreilly.com/radar-contributors/">Radar Contributors</a></li>
                                         <li id="menu-item-48510" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48510"><a href="http://oreilly.com/academic/">Academic Solutions</a></li>
                                         <li id="menu-item-48511" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48511"><a href="http://oreilly.com/jobs/">Jobs</a></li>
                                         <li id="menu-item-48512" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48512"><a href="http://oreilly.com/contact.html">Contacts</a></li>
                                         <li id="menu-item-48513" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48513"><a href="http://oreilly.com/about/">Corporate Information</a></li>
                                         <li id="menu-item-48514" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48514"><a href="http://press.oreilly.com/index.html">Press Room</a></li>
                                         <li id="menu-item-48515" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48515"><a href="http://oreilly.com/oreilly/privacy.csp">Privacy Policy</a></li>
                                         <li id="menu-item-48516" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48516"><a href="http://oreilly.com/terms/">Terms of Service</a></li>
                                         <li id="menu-item-48517" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48517"><a href="http://oreilly.com/oreilly/author/intro.csp">Writing for O&#8217;Reilly</a></li>
                                         <li id="menu-item-68743" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-68743"><a href="http://www.oreilly.com/about/editorial_independence.html">Editorial Independence</a></li>
                                      </ul>
                                   </div>
                                </div>
                                <!-- style="width:25%;" -->
                                <div class="contentSectionContainer">
                                   <h3>Community</h3>
                                   <div class="menu-community-2-container">
                                      <ul id="menu-community-2" class="menu">
                                         <li id="menu-item-48518" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48518"><a href="http://oreilly.com/authors/">Authors</a></li>
                                         <li id="menu-item-48519" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48519"><a href="http://oreilly.com/community/">Community &#038; Featured Users</a></li>
                                         <li id="menu-item-48520" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48520"><a href="http://forums.oreilly.com/">Forums</a></li>
                                         <li id="menu-item-48521" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48521"><a href="https://members.oreilly.com/">Membership</a></li>
                                         <li id="menu-item-48522" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48522"><a href="http://elists.oreilly.com/">Newsletters</a></li>
                                         <li id="menu-item-48523" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48523"><a href="http://answers.oreilly.com/">O&#8217;Reilly Answers</a></li>
                                         <li id="menu-item-48524" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48524"><a href="http://oreilly.com/feeds/">RSS Feeds</a></li>
                                         <li id="menu-item-56120" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-56120"><a href="http://chimera.labs.oreilly.com/">O&#8217;Reilly Chimera (beta)</a></li>
                                      </ul>
                                   </div>
                                </div>
                                <!-- style="width:25%;" -->
                                <div class="contentSectionContainer">
                                   <h3>Partner Sites</h3>
                                   <div class="menu-partner-sites-container">
                                      <ul id="menu-partner-sites" class="menu">
                                         <li id="menu-item-48503" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48503"><a target="_blank" href="http://makezine.com/">makezine.com</a></li>
                                         <li id="menu-item-48504" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48504"><a target="_blank" href="http://makerfaire.com/">makerfaire.com</a></li>
                                         <li id="menu-item-55273" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-55273"><a target="_blank" href="http://craftzine.com/">craftzine.com</a></li>
                                         <li id="menu-item-55274" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-55274"><a target="_blank" href="http://igniteshow.com/">igniteshow.com</a></li>
                                         <li id="menu-item-55276" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-55276"><a target="_blank" href="http://blogs.forbes.com/oreillymedia/">O&#8217;Reilly Insights on Forbes.com</a></li>
                                      </ul>
                                   </div>
                                </div>
                                <!-- style="width:24%;" -->
                                <div class="contentSectionContainer">
                                   <h3>Shop O\'Reilly</h3>
                                   <div class="menu-shop-oreilly-container">
                                      <ul id="menu-shop-oreilly" class="menu">
                                         <li id="menu-item-48505" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48505"><a href="http://shop.oreilly.com/category/customer-service.do">Customer Service</a></li>
                                         <li id="menu-item-48506" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48506"><a href="http://shop.oreilly.com/category/customer-service.do">Contact Us</a></li>
                                         <li id="menu-item-48507" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48507"><a href="http://shop.oreilly.com/category/customer-service/shipping-information.do">Shipping Information</a></li>
                                         <li id="menu-item-48508" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48508"><a href="http://shop.oreilly.com/category/customer-service/ordering-payment.do">Ordering &#038; Payment</a></li>
                                         <li id="menu-item-56665" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-56665"><a href="http://oreilly.com/affiliates/">Affiliate Program</a></li>
                                         <li id="menu-item-48509" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-48509"><a href="http://shop.oreilly.com/category/customer-service/oreilly-guarantee.do">The O&#8217;Reilly Guarantee</a></li>
                                      </ul>
                                   </div>
                                </div>
                                <div class="clear"></div>
                             </div>
                             <div class="clear"></div>
                          </div>
                       </div>
                    </div>
                    <!-- #colophon -->
                 </div>
                 <!-- #footer -->
              </div>
              <!-- #wrapper -->
           </body>
        </html>
    ';
}
