<?php

namespace UntLibraries\OpensearchClient\Tests\Request;

use UntLibraries\OpensearchClient\Request\UrlTemplate;

use \PHPUnit_Framework_TestCase;

class UrlTemplateTest extends PHPUnit_Framework_TestCase
{
    const TEST_URL = 'http://example.com/?q={searchTerms}&s={startIndex?}&c={count?}&pw={startPage?}&lan={language?}&in={inputEncoding?}&out={outputEncoding?}';

    const SEARCH_TERMS_FIELD = 'q=%s';

    const COUNT_FIELD = 'c=%s';

    const START_INDEX_FIELD = 's=%s';

    const START_PAGE_FIELD = 'pw=%s';

    const LANGUAGE_FIELD = 'lan=%s';

    const INPUT_ENCODING_FIELD = 'in=%s';

    const OUTPUT_ENCODING_FIELD = 'out=%s';

    /**
     * @dataProvider urlProvider
     */
    public function testUrlTemplate($url)
    {
        $template = new UrlTemplate($url);

        // No assertions
    }

    /**
     * @dataProvider badUrlProvider
     * @expectedException InvalidArgumentException
     */
    public function testUrlTemplateThrowsException($url)
    {
        $template = new UrlTemplate($url);
    }

    /**
     * @dataProvider optionalFieldProvider
     */
    public function testOptionalFields($url, $count)
    {
        $template = new UrlTemplate($url);

        $this->assertCount($count, $template->getOptionalFields());
    }

    public function testGetSearchTerms()
    {
        $template = new UrlTemplate(self::TEST_URL);

        $this->assertEquals(self::SEARCH_TERMS_FIELD, $template->getSearchTerms());
    }

    public function testGetCount()
    {
        $template = new UrlTemplate(self::TEST_URL);

        $this->assertEquals(self::COUNT_FIELD, $template->getCount());
    }

    public function testGetStartIndex()
    {
        $template = new UrlTemplate(self::TEST_URL);

        $this->assertEquals(self::START_INDEX_FIELD, $template->getStartIndex());
    }

    public function testGetStartPage()
    {
        $template = new UrlTemplate(self::TEST_URL);

        $this->assertEquals(self::START_PAGE_FIELD, $template->getStartPage());
    }

    public function testGetLanguage()
    {
        $template = new UrlTemplate(self::TEST_URL);

        $this->assertEquals(self::LANGUAGE_FIELD, $template->getLanguage());
    }

    public function testGetInputEncoding()
    {
        $template = new UrlTemplate(self::TEST_URL);

        $this->assertEquals(self::INPUT_ENCODING_FIELD, $template->getInputEncoding());
    }

    public function testGetOutputEncoding()
    {
        $template = new UrlTemplate(self::TEST_URL);

        $this->assertEquals(self::OUTPUT_ENCODING_FIELD, $template->getOutputEncoding());
    }

    /*
     * DATA PROVIDERS
     */

    public function urlProvider()
    {
        $provider = array(
            array('http://example.com/search?q={searchTerms}&amp;pw={startPage?}'),
            array('http://example.com/?q={searchTerms}&amp;start={startIndex?}&amp;format=rss'),
        );

        return $provider;
    }

    public function badUrlProvider()
    {
        $provider = array(
            array('email@example.com'),
            array('this is a bad url')
        );

        return $provider;
    }

    public function optionalFieldProvider()
    {
        $provider = array(
            array('http://example.com/search?q={searchTerms}&amp;pw={startPage?}', 1),
            array('http://example.com/?q={searchTerms}&amp;start={startIndex?}&amp;format=rss', 1),
            array('http://example.com/?q={searchTerms}&start={startIndex?}&pw={startPage?}&input={inputEncoding?}', 3),
            array('http://example.com/?q={searchTerms}&s={startIndex?}&c={count?}&pw={startPage?}&lan={language?}&in={inputEncoding?}&out={outputEncoding?}', 6),
        );

        return $provider;
    }
}
