<?php

namespace UntLibraries\OpensearchClient\Tests\Document;

use UntLibraries\OpensearchClient\Description\Document;

use \PHPUnit_Framework_TestCase;

class DocumentTest extends PHPUnit_Framework_TestCase
{
	/**
     * @expectedException Exception
     */
	public function testExceptionThrownWhenXMLIsNotWellFormed()
	{
		$xml = file_get_contents('tests/xml/document1.xml');

		// Make the XML to be ill formed
		$xml = " " . $xml;

		$document = new Document;
		$document->load($xml);
	}

	public function testGetDocument()
	{
		$xml = file_get_contents('tests/xml/document2.xml');

		$document = new Document;
		$document->load($xml);

		$this->assertInstanceOf('SimpleXMLElement', $document->getDocument());
	}

	public function testGetTemplate()
	{
		$xml = file_get_contents('tests/xml/document2.xml');

		$document = new Document;
		$document->load($xml);

		$this->assertTrue(filter_var($document->getTemplate(), FILTER_VALIDATE_URL) != false);
	}
}
