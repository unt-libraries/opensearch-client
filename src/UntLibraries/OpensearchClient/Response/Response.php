<?php

namespace UntLibraries\OpensearchClient\Response;

use UntLibraries\OpensearchClient\Response\Entry;
use UntLibraries\OpensearchClient\Library\Constants;
use UntLibraries\OpensearchClient\Request\SearchQuery;

use \SimpleXMLElement;
use \Exception;

/**
 * Parses the full Opensearch response
 */
class Response
{
    /**
     * @var \SimpleXMLElement
     */
    private $feed;

    /**
     * @var array
     */
    private $results = array();

    /**
     * Value from opensearch:totalResults element
     *
     * @var integer
     */
    private $totalResults;

    /**
     * Value from opensearch:startIndex element
     *
     * @var integer
     */
    private $startIndex;

    /**
     * Value from opensearch:itemsPerPage element
     *
     * @var integer
     */
    private $itemsPerPage;

    /**
     * Value calculated from $itemsPerPage and $totalResults
     *
     * @var integer
     */
    private $numPages;

    /**
     * searchQuery instance optionally passed from the search client.
     *
     * Fields are repopulated based on the Query element if it is present in
     * the response document
     *
     * @var UntLibraries\OpensearchClient\Request\SearchQuery
     */
    public $searchQuery;

    /**
     * Constructor
     *
     * @param UntLibraries\OpensearchClient\Request\SearchQuery $searchQuery
     */
    public function __construct(SearchQuery $searchQuery=null)
    {
        if (is_null($searchQuery)) {
            $this->searchQuery = new SearchQuery;
        } else {
            $this->searchQuery = $searchQuery;
        }
    }

    /**
     * Send the query to search engine
     *
     * @return this
     */
    public function load($feed)
    {
        $this->setFeed($feed)
             ->setResults()
             ->setTotalResults()
             ->setStartIndex()
             ->setItemsPerPage()
             ->setNumPages();

        return $this;
    }

    /**
     * Set feed
     *
     * @param string
     * @return this
     */
    public function setFeed($feed, $url=false)
    {
        libxml_use_internal_errors(true);
        try {
            $this->feed = new SimpleXMLElement($feed, 0, $url);
        } catch (Exception $e) {
            throw new Exception(Constants::SIMPLEXML_EXCEPTION_MESSAGE);
        }

        return $this;
    }

    /**
     * Get feed
     *
     * @return \SimpleXmlElement
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * Set results
     *
     * @return this
     */
    public function setResults()
    {
        $namespaces = $this->getFeed()->getNamespaces(TRUE);
        if ($this->getFeed() instanceof SimpleXMLElement) {
            foreach ($this->getFeed()->entry as $entry) {

                $result = new Entry($entry, $namespaces);
                $result->serialize();

                $this->results[] = $result;
            }
        }

        return $this;
    }

    /**
     * Get results
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Has results
     *
     * @return boolean
     */
    public function hasResults()
    {
        return !empty($this->results);
    }

    /**
     * Set totalResults
     *
     * @return this
     */
    private function setTotalResults()
    {

        if ($this->feed instanceof SimpleXMLElement && !empty($this->feed)) {
            $totalResults = $this->feed->children(Constants::OPENSEARCH_NAMESPACE)
                ->totalResults;
            $this->totalResults = (int)$totalResults;
        }
        return $this;
    }

    /**
     * Get totalResults
     *
     * @return integer
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }

    /**
     * Set startIndex
     *
     * @return this
     */
    private function setStartIndex()
    {
        $startIndex = $this->feed->children(Constants::OPENSEARCH_NAMESPACE)
            ->startIndex;
        $this->startIndex = (int)$startIndex;

        return $this;
    }

    /**
     * Get startIndex
     *
     * @return integer
     */
    public function getStartIndex()
    {
        return $this->startIndex;
    }

    /**
     * Set itemsPerPage
     *
     * @return this
     */
    private function setItemsPerPage()
    {
        $itemsPerPage = $this->feed->children(Constants::OPENSEARCH_NAMESPACE)
            ->itemsPerPage;
        $this->itemsPerPage = (int)$itemsPerPage;

        return $this;
    }

    /**
     * Get itemsPerPage
     *
     * @return integer
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * Determines the endIndex for each search
     *
     * @return integer
     */
    public function calculateEndIndex()
    {
        $start = $this->getStartIndex();
        $perPage = $this->getItemsPerPage();
        $total = $this->getTotalResults();

        if ($start+$perPage >= $total) {
            $endIndex = $total;
        } else {
            $endIndex = $perPage + $start;
        }

        return $endIndex;
    }

    /**
     * Set numPages
     *
     * @return this
     */
    private function setNumPages()
    {
        $totalResults = $this->getTotalResults();
        if (0 < $totalResults) {
            $this->numPages = (int)ceil($totalResults / $this->getItemsPerPage());
        }

        return $this;
    }

    /**
     * Get numPages
     *
     * @return integer
     */
    public function getNumPages()
    {
        return $this->numPages;
    }

    /**
     * Get startPage
     *
     * @return integer
     */
    public function getStartPage()
    {
        return $this->startPage;
    }

}
