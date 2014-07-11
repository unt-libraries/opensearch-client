<?php

namespace UntLibraries\OpensearchClient;

use UntLibraries\OpensearchClient\Response\Response;
use UntLibraries\OpensearchClient\Request\SearchQuery;
use UntLibraries\OpensearchClient\Request\Request;
use UntLibraries\OpensearchClient\Request\UrlTemplate;
use UntLibraries\OpensearchClient\Description\Document;
use UntLibraries\OpensearchClient\Library\Constants;

use \InvalidArgumentException;

/**
 * Access point for Document retrieval and search execution.
 */
class Client
{
    /**
     * Object instance modelling the Opensearch search results
     *
     * @var UntLibraries\OpensearchClient\Response\Response
     */
    public $response;

    /**
     * Opensearch search parameters
     *
     * @var UntLibraries\OpensearchClient\Request\SearchQuery
     */
    public $searchQuery;

    /**
     * Object instance modelling the Opensearch Description Document
     *
     * @var UntLibraries\OpensearchClient\Description\Document
     */
    public $document;

    /**
     * User Agent used to configure cURL options
     *
     * @var string
     */
    public $userAgent = Constants::USER_AGENT;

    /**
     * Constructor
     *
     * @param UntLibraries\OpensearchClient\Request\SearchQuery $searchQuery
     * @param UntLibraries\OpensearchClient\Description\Document $document
     */
    public function __construct(SearchQuery $searchQuery=null, Document $document=null)
    {
        if (is_null($searchQuery)) {
            $this->searchQuery = new SearchQuery;
        } else {
            $this->searchQuery = $searchQuery;
        }

        if (is_null($document)) {
            $this->document = new Document;
        } else {
            $this->searchQuery = $searchQuery;
        }
    }

    /**
     * Set userAgent
     *
     * @param string $agent
     * @return this
     */
    public function setUserAgent($agent)
    {
        $this->userAgent = $agent;

        return $this;
    }

    /**
     * Send query to the search engine
     *
     * @param string $template
     * @return UntLibraries\OpensearchClient\Response\Response
     */
    public function search($template)
    {
        $template = new UrlTemplate($template);
        $request = new Request($template, $this->searchQuery);
        $feed = $this->execute($request->getUrl());

        $response = new Response($this->searchQuery);

        $response->load($feed);
        $this->response = $response;

        return $this->response;
    }

    /**
     * Contacts the Opensearch Description Document
     * and retrieves the URL template, which is used to properly
     * formulate search queries.
     *
     * @param string $url
     * @return string
     */
    public function configure($url)
    {
        $rawDocument = $this->execute($url);
        $this->document->load($rawDocument);

        return $this->document->getTemplate();
    }

    /**
     * PHP cURL Wrapper
     *
     * @param string $url
     * @return string
     */
    private function execute($url)
    {
        // URL validated in the UrlTemplate and Document constructors
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);

        return curl_exec($ch);
    }

}
