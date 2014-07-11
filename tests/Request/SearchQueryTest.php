<?php

namespace UntLibraries\OpensearchClient\Tests\Request;

use UntLibraries\OpensearchClient\Request\SearchQuery;

use \PHPUnit_Framework_TestCase;

class SearchQueryTest extends PHPUnit_Framework_TestCase
{
    public function testSearchTermsFormat()
    {
        $searchQuery = new SearchQuery;

        $searchTerms = array("test", "search");
        $searchQuery->setSearchTerms($searchTerms);

        $this->assertInternalType('string', $searchQuery->getSearchTerms());
        $this->assertEquals('test+search', $searchQuery->getSearchTerms());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetCountThrowsExceptionWhenGivenWrongType()
    {
        $searchQuery = new SearchQuery;

        $count = 'count';
        $searchQuery->setCount($count);
    }

    public function testCountIsSet()
    {
        $searchQuery = new SearchQuery;

        $count = 1;
        $searchQuery->setCount($count);

        $this->assertEquals($count, $searchQuery->getCount());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetStartIndexThrowsExceptionWhenGivenWrongType()
    {
        $searchQuery = new SearchQuery;

        $startIndex = array();
        $searchQuery->setStartIndex($startIndex);
    }

    public function testStartIndexIsSet()
    {
        $searchQuery = new SearchQuery;

        $startIndex = 1;
        $searchQuery->setStartIndex($startIndex);

        $this->assertEquals($startIndex, $searchQuery->getStartIndex());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetStartPageThrowsExceptionWhenGivenWrongType()
    {
        $searchQuery = new SearchQuery;

        $startPage = "startPage";
        $searchQuery->setStartPage($startPage);
    }

    public function testStartPageIsSet()
    {
        $searchQuery = new SearchQuery;

        $startPage = 1;
        $searchQuery->setStartPage($startPage);

        $this->assertEquals($startPage, $searchQuery->getStartPage());
    }

    public function testGetSetLanguage()
    {
        $searchQuery = new SearchQuery;

        $language = 'en';
        $searchQuery->setLanguage($language);

        $this->assertEquals($language, $searchQuery->getLanguage());
    }

    public function testGetSetInputEncoding()
    {
        $searchQuery = new SearchQuery;

        $inputEncoding = 'UTF-8';
        $searchQuery->setInputEncoding($inputEncoding);

        $this->assertEquals($inputEncoding, $searchQuery->getInputEncoding());
    }

    public function testGetSetOutputEncoding()
    {
        $searchQuery = new SearchQuery;

        $outputEncoding = 'UTF-8';
        $searchQuery->setOutputEncoding($outputEncoding);

        $this->assertEquals($outputEncoding, $searchQuery->getOutputEncoding());
    }

}
