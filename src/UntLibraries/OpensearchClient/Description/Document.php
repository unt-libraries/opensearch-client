<?php

namespace UntLibraries\OpensearchClient\Description;

use UntLibraries\OpensearchClient\Library\Constants;

use \SimpleXMLElement;

/**
 * Parses the Opensearch Description Document XML
 */
class Document
{
    /**
     * URL to the OpenSearch Description Document
     *
     * @var string
     */
    private $documentUrl;

    /**
     * Opensearch Description Document
     *
     * @var SimpleXMLElement
     */
    private $document;

    /**
     * The Url element that contains the search template attribute
     *
     * @var \SimpleXMLElement
     */
    private $url;

    /**
     * The url template for constructing searches
     *
     * @var string
     */
    private $template;

    /**
     * Initialize the SimpleXMLElement instance
     * Set the template
     *
     * @return this
     */
    public function load($document)
    {
        libxml_use_internal_errors(true);

        try {
            $this->document = new SimpleXmlElement($document);
        } catch (Exception $e) {
            throw new Exception(Constants::SIMPLEXML_EXCEPTION_MESSAGE);
        }

        $this->setTemplate();

        return $this;
    }

    /**
     * Iterates through the Url elements and finds on with type application/atom+xml
     * sets it to $this->template
     *
     * @return this
     */
    private function setTemplate()
    {
        if ($this->document) {
            foreach ($this->document->Url as $url) {
                if (0 == strcmp($url->attributes()->type, Constants::ATOM_MIME_TYPE)) {
                    $this->url = $url;
                    $this->template = (string)$url->attributes()->template;
                }
            }
        }

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Get document
     *
     * @return \SimpleXMLElement
     */
    public function getDocument()
    {
        return $this->document;
    }
}
