<?php

namespace UntLibraries\OpensearchClient\Tests\Response;

use UntLibraries\OpensearchClient\Response\Entry;

use \SimpleXMLElement;
use \PHPUnit_Framework_TestCase;

class EntryTest extends PHPUnit_Framework_TestCase
{
	public function testLink()
	{
		$xml = file_get_contents('tests/xml/results1.xml');

		$document = new SimpleXMLElement($xml);
		$namespaces = $document->getNamespaces(true);

		$entry = new Entry($document->entry[0], $namespaces);
		$entry->serialize();

		$this->assertTrue(filter_var($entry->link, FILTER_VALIDATE_URL) != false);
	}

	public function testPublishedandUpdated()
	{
		$xml = file_get_contents('tests/xml/results1.xml');

		$document = new SimpleXMLElement($xml);
		$namespaces = $document->getNamespaces(true);

		$entry = new Entry($document->entry[0], $namespaces);
		$entry->serialize();

		$this->assertInstanceOf('DateTime', $entry->published);
		$this->assertInstanceOf('DateTime', $entry->updated);
	}

	public function testThumbnail()
	{
		$xml = file_get_contents('tests/xml/results1.xml');

		$document = new SimpleXMLElement($xml);
		$namespaces = $document->getNamespaces(true);

		$entry = new Entry($document->entry[0], $namespaces);
		$entry->serialize();

		$this->assertFalse(is_null($entry->thumbnail));
		$this->assertTrue(filter_var($entry->thumbnail, FILTER_VALIDATE_URL) != false);
	}
}