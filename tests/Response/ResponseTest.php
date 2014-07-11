<?php

namespace UntLibraries\OpensearchClient\Tests\Response;

use UntLibraries\OpensearchClient\Response\Response;
use UntLibraries\OpensearchClient\Request\SearchQuery;

use \PHPUnit_Framework_TestCase;

class ResponseTest extends PHPUnit_Framework_TestCase
{
	/**
     * @expectedException Exception
     */
	public function testExceptionThrownWhenXMLIsNotWellFormed()
	{
		$xml = file_get_contents('tests/xml/results1.xml');
		$xml = " " . $xml;
		$response = new Response;

		$response->setFeed($xml);
	}

	public function testSetFeed()
	{
		$xml = file_get_contents('tests/xml/results1.xml');

		$response = new Response;
		$response->setFeed($xml);

		$this->assertInstanceOf('SimpleXMLElement', $response->getFeed());
	}

	public function testGetResults()
	{
		$xml = file_get_contents('tests/xml/results1.xml');
		$response = new Response;

		$response->load($xml);

		$this->assertCount(4, $response->getResults());
		$this->assertContainsOnlyInstancesOf('UntLibraries\OpensearchClient\Response\Entry', $response->getResults());
	}

	public function testNoEntryElementsInResults()
	{
		$xml = file_get_contents('tests/xml/resultNoEntries.xml');
		$response = new Response;

		$response->load($xml);

		$this->assertCount(0, $response->getResults());
	}

	public function testGetTotalRestults()
	{
		$xml = file_get_contents('tests/xml/results1.xml');
		$response = new Response;

		$response->load($xml);

		$this->assertEquals(893, $response->getTotalResults());

	}

	public function testGetStartIndex()
	{
		$xml = file_get_contents('tests/xml/results1.xml');
		$response = new Response;

		$response->load($xml);

		$this->assertEquals(1, $response->getStartIndex());
	}

	public function testGetItemsPerPage()
	{
		$xml = file_get_contents('tests/xml/results1.xml');
		$response = new Response;

		$response->load($xml);

		$this->assertEquals(10, $response->getItemsPerPage());
	}

	public function testGetNumPages()
	{
		$xml = file_get_contents('tests/xml/results1.xml');
		$response = new Response;

		$response->load($xml);

		$this->assertEquals(90, $response->getNumPages());
	}

}
