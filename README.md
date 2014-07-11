#UNT Libraries OpensearchClient
PHP Opensearch search client written to the [Opensearch 1.1 Draft 5 Specification](http://www.opensearch.org/Specifications/OpenSearch/1.1), for search results in the Atom Syndication Format.
### Requirements

- PHP >= 5.3
   - SimpleXML enabled
   - cURL support enabled

## Usage

```php

$client = new Client();

// Get the URL template from the description document
$template = $client->configure('http://example.com/opensearch/document.xml');

// Set some search parameters
$keys = ['Search', 'with', 'these', 'keys'];
$client->searchQuery->setSearchTerms($keys);
$client->searchQuery->setStartPage(4);

// Send the query 
$response = $client->search($template);

// do something with the results
$entry1 = $response->getResults()[0]
 
```

## Client

Provides an entry point for retrieving an Opensearch Description and executing searches based on the specification of URL template provided by the document.

#### Properties
##### document
```php
Document Client::document
```
- Instance of the Document object
    - Not populated until `Client::configure()` has been executed 

##### searchQuery
```php
SearchQuery Client::searchQuery
```
- Instance of the `SearchQuery` object

##### response
```php
Response Client::response
```
- Instance of the `Response` object
    - Not set until `Client::search()` has been executed

#### Methods
##### setUserAgent()
```php
Client Client::setUserAgent(string $userAgent)
```
- Used to set the user agent when `Client::configure()` and `Client::search()` execute

##### configure()
```php
string Client::configure(string $url)
```
- Configure the search parameters; 
- Returns the Opensearch Description `<Url>` template attribute

##### search()
```php
Response Client::search(string $template)
```
- Takes the templates returned by `Client::configure()`
- Executes the search with the current state of `Client::searchQuery` 


## Document

Parses the Description document upon retrieval and provides two methods for retrieving the information


#### Methods
##### getTemplate()
```php
string Document::getTemplate()
```
- Returns the template attribute from the `<Url>` element
- Example: `http://example.com/search?q={searchTerms}&amp;pw={startPage?}`

##### getDocument()
```php
SimpleXMLElement Document::getDocument()
```
- Returns the entire Description Document loaded into an instance of SimpleXMLElement

## SearchQuery


This object is used to set the parameters for the search. It is compatible with unprefixed parameters outlined in the required and options paramenters section of the [Opensearch 1.1 Draft 5 Specification](http://www.opensearch.org/Specifications/OpenSearch/1.1#Required_template_parameters)


This object is used in conjunction with `UntLibraries\OpensearchClient\Request\Request` and `UntLibraries\OpensearchClient\Request\UrlTemplate`, niether of which are client facing in the current state of this library. 



#### Methods
__Setting search keywords__ (required according to the Opensearch Specification 1.1)
##### setSearchTerms()
```php
SearchQuery SearchQuery::setSearchTerms(array $keys)
```
- Set the Opensearch `searchTerms` parameter
- Must be set prior to executing `Client::search($template)`


__Other search parameters__

If any of these options are set but are not included in the Opensearch Description URL template, then they will safely be ignored.

##### setCount()
```php
SearchQuery SearchQuery::setCount(int $count)
```
- Set the Opensearch `count` parameter

##### setStartIndex()
```php
SearchQuery SearchQuery::setStartIndex(int $startIndex)
```
- Set the Opensearch `startIndex` parameter

##### setStartPage()
```php
SearchQuery SearchQuery::setStartPage(int $startPage)
```
- Set the Opensearch `startPage` parameter

##### setLanguage()
```php
SearchQuery SearchQuery::setLanguage(string $language)
```
- Set the Opensearch `language` parameter

##### setInputEncoding()
```php
SearchQuery SearchQuery::setInputEncoding(string $inputEncoding)
```
- Set the Opensearch `inputEncoding` parameter

##### setOutputEncoding()
```php
SearchQuery SearchQuery::setOutputEncoding(string $outputEncoding)
```
- Set the Opensearch `outputEncoding` parameter

## Response

Access point to information relating to the search result set

#### Methods

##### getResults()
```php
array Response::getResults()
```
- Returns an array of Entry instances

##### hasResults()
```php
bool Response::hasResults()
```
- Returns `true` if the `Response::getResults()` is not empty

##### getTotalResults
```php
int Response::getTotalResults()
```
- Returns an integer representing the total number of results 

##### getStartIndex()
```php
int Response::getStartIndex()
```
- Returns integer representing the index of the search result in the current result set

##### calculateEndIndex()
```php
int Response::calculateEndIndex()
```
- Calculates the index of the last search result in the current result set

##### getItemsPerPage()
```php
int Response::getItemsPerPage()
```
- Returns the number of search results in the current result set 

##### getNumPages()
```php
int Response::getNumPages()
```
- Calculates the number of result sets (pages) available given the values of `Response::getItemsPerPage()` and `Response::getTotalResults()`

##### getFeed()
```php
SimpleXMLElement Response::getFeed()
```
- Returns entire Opensearch response loaded into a SimpleXMLElement instance


## Entry

Parses the individual search results and makes them available via public properties.

#### Properties
##### title
```php
string Entry::title
```
- The value of the `<title>` element of the `<entry>` tree

##### link
```php
string Entry::link
```
- The value of the `<link>` element of the `<entry>` tree

##### id
```php
string Entry::id
```

- The value of the `<id>` element of the `<entry>` tree

##### updated
```php
Datetime Entry::updated
```
- The value of the `<updated>` element of the `<entry>` tree

##### published
```php
Datetime Entry::published
```

- The value of the `<published>` element of the `<entry>` tree

##### content
```php
string Entry::content
```
- The value of the `<content>` element of the `<entry>` tree

##### thumbnail
```php
string Entry::thumbnail
```

- If present, this is the url attribute of the `<media:thumbnail>` element of the `<entry>` tree
	
  __Note__: this element is not part of the Atom standard.


#### Methods

##### getEntry()
```php
SimpleXMLElement Entry::getEntry()
```
* Returns the single `<entry>` subtree loaded into a SimpleXML instance
