<?php

namespace UntLibraries\OpensearchClient\Request;

use UntLibraries\OpensearchClient\Request\SearchQuery;
use UntLibraries\OpensearchClient\Request\UrlTemplate;

use \Exception;

/**
 * Accepts SearchQuery and UrlTemplate as parameters and
 * compares their properties to generate a URL with a query string
 * the conforms to the Opensearch Description Document's URL template
 * specification.
 */
class Request
{
    /**
     * Instance of UrlTemplate
     *
     * Compared with $searchQuery to generate a query to the
     * search engine
     *
     * @var UntLibraries\OpensearchClient\Request\UrlTemplate
     */
    public $template;

    /**
     * Instance of SearchQuery
     *
     * Compared with $template to generate a query to the
     * search engine
     *
     * @var UntLibraries\OpensearchClient\Request\SearchQuery
     */
    public $searchQuery;

    /**
     * Array of eligible query fields for the query string
     *
     * @var array
     */
    public $queryFields = array();

    /**
     * Dynamically generated query string to be appended the URL
     *
     * @var string
     */
    public $queryString;

    /**
     * Constructor
     *
     * @param UntLibraries\OpensearchClient\Request\UrlTemplate
     * @param UntLibraries\OpensearchClient\Request\SearchQuery
     */
    public function __construct(UrlTemplate $template, SearchQuery $query)
    {
        $this->template = $template;

        if (is_null($query->searchTerms)) {
            throw new Exception('Opensearch requires that the searchTerms field is set. Use SearchQuery::setSearchTerms()');
        } else {
            $this->searchQuery = $query;
        }
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        $this->setUrl();
        return $this->url;
    }

    /**
     * Set url
     *
     * @return this
     */
    public function setUrl()
    {
        $baseUrl = $this->template->getBaseUrl();
        $this->setQueryFields();
        $this->setQueryString();

        if (!empty($baseUrl) && !empty($this->queryString)) {
            $this->url = $baseUrl . $this->queryString;
        }
        return $this;
    }

    /**
     * Get all the available query field templates from
     * instance of UrlTemplate and put them into $queryFields
     *
     * @return this
     */
    private function setQueryFields()
    {
        $this->queryFields['searchTerms'] = $this->template->getSearchTerms();

        if (!is_null($this->template->getStartPage())) {
            $this->queryFields['startPage'] = $this->template->getStartPage();
        }

        if (!is_null($this->template->getCount())) {
            $this->queryFields['count'] = $this->template->getCount();
        }

        if (!is_null($this->template->getStartIndex())) {
            $this->queryFields['startIndex'] = $this->template->getStartIndex();
        }

        if (!is_null($this->template->getLanguage())) {
            $this->queryFields['language'] = $this->template->getLanguage();
        }

        if (!is_null($this->template->getInputEncoding())) {
            $this->queryFields['inputEncoding'] = $this->template->getInputEncoding();
        }

        if (!is_null($this->template->getOutputEncoding())) {
            $this->queryFields['outputEncoding'] = $this->template->getOutputEncoding();
        }

        return $this;
    }

    /**
     * Iterates through the parameters and creates a query
     * string with the corresponding class variables
     *
     * @return this
     */
    public function setQueryString()
    {
        $implosion = array();

        foreach ($this->queryFields as $parameter => $value) {
            if ($this->searchQuery->$parameter) {
                $implosion[] = sprintf($value, $this->searchQuery->$parameter);
            }
        }

        // Get any addition query fields that were parsed but are not Openseach
        // parameters. Example: format=atom
        $additionalFields = get_object_vars($this->template);

        // Queue up additional fields to be imploded
        $implosion = array_merge($implosion, $additionalFields);

        // Implode the array, delimited with &, and prepend the ?
        $this->queryString = sprintf('?%s', implode('&', $implosion));

        return $this;
    }

}
