<?php

namespace UntLibraries\OpensearchClient\Request;

use UntLibraries\OpensearchClient\Library\Constants;
use \InvalidArgumentException;

/**
 * Parses a Opensearch Description URL template attribute
 * and determines the required and optional parameters, and
 * sets up the corresponding query string fields names for later use
 * in the Request object.
 */
class UrlTemplate
{
    /**
     * Raw URL Template from the Description Document
     *
     * @var string
     */
    private $template;

    /**
     * http://example.com/
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Query string stripped from the URL Template
     *
     * @var string
     */
    private $queryString;

    /**
     * Array of the individual query fields
     *
     * @var array
     */
    private $queryParts;

    /**
     * Query field for searchTerms parameter
     *
     * @var string
     */
    private $searchTerms;

    /**
     * Query field for count parameter
     *
     * @var string
     */
    private $count;

    /**
     * Query field for startIndex parameter
     *
     * @var string
     */
    private $startIndex;

    /**
     * Query field for startPage parameter
     *
     * @var string
     */
    private $startPage;

    /**
     * Query field for language parameter
     *
     * @var string
     */
    private $language;

    /**
     * Query field for inputEncoding parameter
     *
     * @var string
     */
    private $inputEncoding;

    /**
     * Query field for outputEncoding parameter
     *
     * @var string
     */
    private $outputEncoding;

    /**
     * Array of parameter that are option according to the URL Template
     * Optional parameter include a ?, and look like {startPage?}
     *
     * @var array
     */
    private $optionalFields = array();

    /**
     * Constructor
     *
     * @param string $template
     */
    public function __construct($template=null)
    {
        if (!is_null($template)) {
            $this->setTemplate($template)->parse();
        }
    }

    /**
     * Optional method for setting the template outside of the constructor
     *
     * @param string $template
     * @return this
     */
    private function setTemplate($template)
    {
        // Add better validation to for the sake of CURL
        if (filter_var($template, FILTER_VALIDATE_URL)) {
            // Decode escaped html
            $this->template = html_entity_decode($template);
        } else {
            throw new InvalidArgumentException("The provided URL is not valid");
        }

        return $this;
    }

    /**
     * Parse the Opensearch Description Document
     *
     * @return this
     */
    public function parse($template=null)
    {
        if (is_null($this->template) && is_null($template)) {

            throw new InvalidArgumentException('You must provide a URL to be parsed');

        } elseif (!is_null($template)) {

            $this->setTemplate($template);
        }

        $this->setBaseUrl()
            ->setQueryString()
            ->setQueryParts()
            ->setSearchTerms()
            ->setCount()
            ->setStartIndex()
            ->setStartPage()
            ->setLanguage()
            ->setInputEncoding()
            ->setOutputEncoding();

        return $this;
    }

    /**
     * Set baseUrl
     *
     * @return this
     */
    private function setBaseUrl()
    {
        $parsedUrl = parse_url($this->template);

        // Sets $baseUrl in the form of http://example.com/path/
        $this->baseUrl = sprintf(
            '%s://%s%s',
            $parsedUrl['scheme'], $parsedUrl['host'], $parsedUrl['path']
        );

        return $this;
    }

    /**
     * Get baseUrl
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Set queryString
     *
     * @return this
     */
    private function setQueryString()
    {
        $parsedUrl = parse_url($this->template);

        $this->queryString = $parsedUrl['query'];

        return $this;
    }

    /**
     * Get queryString
     *
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Set queryParts
     *
     * @return this
     */
    private function setQueryParts()
    {
        $parsedQuery = array();
        parse_str($this->queryString, $parsedUrl);

        $this->queryParts = $this->trimParameters($parsedUrl);

        return $this;
    }

    /**
     * Get queryParts
     *
     * @return array
     */
    public function getQueryParts()
    {
        return $this->queryParts;
    }

    /**
     * Trim Parameters of additional characters
     *
     * @param array $queryParts
     * @return array
     */
    private function trimParameters($queryParts)
    {
        foreach ($queryParts as $field => $parameter) {
            $parameter = trim($parameter, '{}');

            if (preg_match('/\?$/', $parameter)) {
                // Optional parameters have a trailing ?
                $parameter = rtrim($parameter, "?");

                $this->optionalFields[] = $parameter;

                $queryParts[$field] = $parameter;

            } elseif (!in_array($parameter, Constants::$parameters)) {
                $this->$field = sprintf('%s=%s', $field, $parameter);

            } else {
                $queryParts[$field] = $parameter;
            }
        }

        $queryParts = array_flip($queryParts);

        return $queryParts;
    }

    /**
     * Get optionalField
     *
     * @return array
     */
    public function getOptionalFields()
    {
        return $this->optionalFields;
    }

    /**
     * Set searchTerms
     *
     * @return this
     */
    private function setSearchTerms()
    {
        if (isset($this->queryParts[Constants::PARAMETER_SEARCH_TERMS])) {
            $this->searchTerms = $this->queryParts[Constants::PARAMETER_SEARCH_TERMS] . Constants::INI_APPEND;
        }

        return $this;
    }

    /**
     * Get searchTerms
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
     * @return this
     */
    private function setCount()
    {
        if (isset($this->queryParts[Constants::PARAMETER_COUNT])) {
            $this->count = $this->queryParts[Constants::PARAMETER_COUNT] . Constants::INI_APPEND;
        }

        return $this;
    }

    /**
     * Get count
     *
     * @return string
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set startIndex
     *
     * @return this
     */
    private function setStartIndex()
    {
        if (isset($this->queryParts[Constants::PARAMETER_START_INDEX])) {
            $this->startIndex = $this->queryParts[Constants::PARAMETER_START_INDEX] . Constants::INI_APPEND;
        }

        return $this;
    }

    /**
     * Get startIndex
     *
     * @return string
     */
    public function getStartIndex()
    {
        return $this->startIndex;
    }

    /**
     * set startPage
     *
     * @return this
     */
    private function setStartPage()
    {
        if (isset($this->queryParts[Constants::PARAMETER_START_PAGE])) {
            $this->startPage = $this->queryParts[Constants::PARAMETER_START_PAGE] . Constants::INI_APPEND;
        }

        return $this;
    }

    /**
     * Get startPage
     *
     * @return string
     */
    public function getStartPage()
    {
        return $this->startPage;
    }

    /**
     * Set language
     *
     * @return this
     */
    private function setLanguage()
    {
        if (isset($this->queryParts[Constants::PARAMETER_LANGUAGE])) {
            $this->language = $this->queryParts[Constants::PARAMETER_LANGUAGE] . Constants::INI_APPEND;
        }

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
     * @return this
     */
    private function setInputEncoding()
    {
        if (isset($this->queryParts[Constants::PARAMETER_INPUT_ENCODING])) {
            $this->inputEncoding = $this->queryParts[Constants::PARAMETER_INPUT_ENCODING] . Constants::INI_APPEND;
        }

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
     * @return this
     */
    private function setOutputEncoding()
    {
        if (isset($this->queryParts[Constants::PARAMETER_OUTPUT_ENCODING])) {
            $this->outputEncoding = $this->queryParts[Constants::PARAMETER_OUTPUT_ENCODING] . Constants::INI_APPEND;
        }

        return $this;
    }

    /**
     * Get OutputEncoding
     *
     * @return string
     */
    public function getOutputEncoding()
    {
        return $this->outputEncoding;
    }
}

