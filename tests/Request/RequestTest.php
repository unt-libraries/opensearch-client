<?php

namespace UntLibraries\OpensearchClient\Tests\Request\RequestTest;

use UntLibraries\OpensearchClient\Request\Request;

use \PHPUnit_Framework_TestCase;

class RequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     */
    public function testConstructorsThrowsException()
    {
        $template = $this->getMockBuilder('UntLibraries\OpensearchClient\Request\UrlTemplate')
                         ->disableOriginalConstructor()
                         ->getMock();

        $query = $this->getMock('UntLibraries\OpensearchClient\Request\SearchQuery');

        $request = new Request($template, $query);
        $request->getUrl();
    }

    public function testQueryStringIsFormulatedWithSingleField()
    {
        $template = $this->getMockBuilder('UntLibraries\OpensearchClient\Request\UrlTemplate')
                         ->disableOriginalConstructor()
                         ->getMock();

        $template->expects($this->any())
                 ->method('getBaseUrl')
                 ->will($this->returnValue('http://example.com/'));

        $template->expects($this->any())
                 ->method('getSearchTerms')
                 ->will($this->returnValue('q=%s'));

        $query = $this->getMock('UntLibraries\OpensearchClient\Request\SearchQuery');

        $query->searchTerms = 'term1+term2';

        $request = new Request($template, $query);
        $result = $request->getUrl();

        $this->assertEquals('http://example.com/?q=term1+term2', $result);
    }

    public function testQueryStringIsFormulatedWithMultipleFields()
    {
        $template = $this->getMockBuilder('UntLibraries\OpensearchClient\Request\UrlTemplate')
                         ->disableOriginalConstructor()
                         ->getMock();

        $template->expects($this->any())
                 ->method('getBaseUrl')
                 ->will($this->returnValue('http://example.com/'));

        $template->expects($this->any())
                 ->method('getSearchTerms')
                 ->will($this->returnValue('q=%s'));

        $template->expects($this->any())
                 ->method('getStartIndex')
                 ->will($this->returnValue('index=%s'));

        $template->expects($this->any())
                 ->method('getLanguage')
                 ->will($this->returnValue('lang=%s'));

        $query = $this->getMock('UntLibraries\OpensearchClient\Request\SearchQuery');

        $query->searchTerms = 'term1';
        $query->startIndex = 3;
        $query->language = 'en-us';

        $request = new Request($template, $query);
        $result = $request->getUrl();

        $this->assertEquals('http://example.com/?q=term1&index=3&lang=en-us', $result);
    }

    public function testSearchParametersWithoutFieldsAreNotIncluded()
    {
        $template = $this->getMockBuilder('UntLibraries\OpensearchClient\Request\UrlTemplate')
                         ->disableOriginalConstructor()
                         ->getMock();

        $template->expects($this->any())
                 ->method('getBaseUrl')
                 ->will($this->returnValue('http://example.com/'));

        $template->expects($this->any())
                 ->method('getSearchTerms')
                 ->will($this->returnValue('q=%s'));

        $query = $this->getMock('UntLibraries\OpensearchClient\Request\SearchQuery');

        $query->searchTerms = 'term1+term2';
        $query->startIndex = 3;
        $query->language = 'en-us';

        $request = new Request($template, $query);
        $result = $request->getUrl();

        $this->assertEquals('http://example.com/?q=term1+term2', $result);
    }

    public function testAdditionalQueryFieldsAreAddedToQueryString()
    {
        $template = $this->getMockBuilder('UntLibraries\OpensearchClient\Request\UrlTemplate')
                         ->disableOriginalConstructor()
                         ->getMock();

        $template->expects($this->any())
                 ->method('getBaseUrl')
                 ->will($this->returnValue('http://example.com/'));

        $template->expects($this->any())
                 ->method('getSearchTerms')
                 ->will($this->returnValue('q=%s'));

        // Additional parameters are added as instance variables
        $template->format = 'format=atom';

        $query = $this->getMock('UntLibraries\OpensearchClient\Request\SearchQuery');

        $query->searchTerms = 'term1+term2';
        $query->startIndex = 3;
        $query->language = 'en-us';

        $request = new Request($template, $query);
        $result = $request->setQueryString();

        $this->assertTrue(strpos($request->queryString, 'format=atom') !== false);
    }
}
