<?php

namespace UntLibraries\OpensearchClient\Request;

use UntLibraries\OpensearchClient\Request\Url;
use \InvalidArgumentException;

/**
 * For setting Opensearch search parameters
 */
class SearchQuery
{
    /**
     * Collection of keys words delimited by '+'
     *
     * @var string
     */
    public $searchTerms;

    /**
     * Number of search results desired by the search client
     *
     * @var integer
     */
    public $count;

    /**
     * Index of the first result desired by the search client
     *
     * @var integer
     */
    public $startIndex;

    /**
     * Page number of the set of search results desired by the search client
     *
     * @var integer
     */
    public $startPage;

    /**
     * Langague desired by the search client
     *
     * @var string
     */
    public $language;

    /**
     * Input encoding desired by the search client
     *
     * @var string
     */
    public $inputEncoding;

    /**
     * Output encoding desired by the search client
     *
     * @var string
     */
    public $outputEncoding;

    /**
     * Set searchTerms
     *
     * Recevies an array of keywords.
     * Sanitizes the terms and implodes them into a delimited string
     *
     * @param array $searchTerms
     * @return this
     */
    public function setSearchTerms($searchTerms)
    {
        // Remove any empty elements
        $searchTerms = array_filter($searchTerms);

        // Trim all the values
        $searchTerms = array_map(function($value) {return trim($value);}, $searchTerms);

        // Handle multiple words
        $this->searchTerms = implode('+', $searchTerms);

        return $this;
    }

    /**
     * Get searchResults
     *
     * @return string
     */
    public function getSearchTerms()
    {
        return $this->searchTerms;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return this
     */
    public function setCount($count)
    {
        // PHP does not allow for type hinting primitives
        if (gettype($count) != 'integer') {
            throw new InvalidArgumentException('Parameter must be an integer');
        } else {
            $this->count = $count;
        }

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set startIndex
     *
     * @param integer $startIndex
     * @return this
     */
    public function setStartIndex($startIndex)
    {
        // PHP does not allow for type hinting primitives
        if (gettype($startIndex) != 'integer') {
            throw new InvalidArgumentException('Parameter must be an integer');
        } else {
            $this->startIndex = $startIndex;
        }

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
     * Set startPage
     *
     * @param integer $startPage
     * @return this
     */
    public function setStartPage($startPage=1)
    {
        // PHP does not allow for type hinting primitives
        if (gettype($startPage) != 'integer') {
            throw new InvalidArgumentException('Parameter must be an integer');
        }
        $this->startPage = $startPage;

        return $this;
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

    /**
     * Set language
     *
     * @param string $language
     * @return this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set inputEncoding
     *
     * @param string $inputEncoding
     * @return this
     */
    public function setInputEncoding($inputEncoding)
    {
        $this->inputEncoding = $inputEncoding;

        return $this;
    }

    /**
     * Get inputEncoding
     *
     * @return string
     */
    public function getInputEncoding()
    {
        return $this->inputEncoding;
    }

    /**
     * Set outputEncoding
     *
     * @param string $outputEncoding
     * @return this
     */
    public function setOutputEncoding($outputEncoding)
    {
        $this->outputEncoding = $outputEncoding;

        return $this;
    }

    /**
     * Get outputEncoding
     *
     * @return string
     */
    public function getOutputEncoding()
    {
        return $this->outputEncoding;
    }
}
