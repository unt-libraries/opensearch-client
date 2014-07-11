<?php

namespace UntLibraries\OpensearchClient\Response;

use \DateTime;
use \SimpleXMLElement;

/**
 * Parses the individual search results
 */
class Entry
{
    /**
     * A single array element from UntdlSearch::feed[]
     *
     * @var SimpleXMLElement
     */
    private $entry;

    /**
     * Namespaces gathered from UntdlSearch
     *
     * @var array
     */
    public $namespaces;

    /**
     * Result Title
     *
     * @var string
     */
    public $title;

    /**
     * Link to source
     *
     * @var string
     */
    public $link;

    /**
     * ID of the entry
     *
     * @var string
     */
    public $id;

    /**
     * Timestamp of last update to the entry
     *
     * @var \DateTime
     */
    public $updated;

    /**
     * Timestamp of publishing
     *
     * @var \DateTime
     */
    public $published;

    /**
     * Description of the entry (Snippet)
     *
     * @var string
     */
    public $content;

    /**
     * URL of the image
     *
     * @var string
     */
    public $thumbnail;

    /**
     * Constructor
     *
     * @param \SimpleXMLElement $entry
     * @param array $namespaces
     */
    public function __construct(SimpleXMLElement $entry, array $namespaces)
    {
        $this->entry = $entry;
        $this->namespaces = $namespaces;
    }

    /**
     * Serialize the entry into the class properties
     *
     * @return this
     */
    public function serialize() {
        $this->setTitle()
            ->setLink()
            ->setId()
            ->setUpdated()
            ->setPublished()
            ->setContent()
            ->setThumbnail();

        return $this;
    }

    /**
     * Set title
     *
     * @return this
     */
    private function setTitle()
    {
        if (isset($this->entry->title)) {
            $this->title = $this->entry->title;
        }

        return $this;
    }

    /**
     * Set link
     *
     * @return this
     */
    private function setLink()
    {
        if (isset($this->entry->link)) {
            $this->link = $this->entry
                ->link
                ->attributes()
                ->href;
        }

        return $this;
    }

    /**
     * Set id
     *
     * @return this
     */
    private function setId()
    {
        if (isset($this->entry->id)) {
            $this->id = $this->entry->id;
        }

        return $this;
    }

    /**
     * Set updated
     *
     * @return this
     */
    private function setUpdated()
    {
        if (isset($this->entry->updated)) {
            $this->updated = new DateTime($this->entry->updated);
        }

        return $this;
    }

    /**
     * Set published
     *
     * @return this
     */
    private function setPublished()
    {
        if (isset($this->entry->published)) {
            $this->published = new DateTime($this->entry->published);
        }

        return $this;
    }

    /**
     * Set content
     *
     * @return this
     */
    private function setContent()
    {
        if (isset($this->entry->content)) {
            $this->content = $this->entry->content;
        }

        return $this;
    }

    /**
     * Set thumbnail
     *
     * @return this
     */
    private function setThumbnail()
    {
        if (isset($this->namespaces['media'])) {
            $thumbnail = $this->entry->children($this->namespaces['media'])->thumbnail[0];
            if ($thumbnail instanceof SimpleXMLElement) {
                if (property_exists($thumbnail->attributes(), 'url')) {
                    $this->thumbnail = $thumbnail->attributes()->url;
                }
            }
        }

        return $this;
    }

    /**
     * Get entry
     *
     * Provides access to the SimpleXMLElement Instance
     * Allows for accessing elements that were used to extend
     * Atom feed.
     *
     * @return SimpleXMLElement
     */
    public function getEntry()
    {
        return $this->entry;
    }
}
