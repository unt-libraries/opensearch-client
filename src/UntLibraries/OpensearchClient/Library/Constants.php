<?php

namespace UntLibraries\OpensearchClient\Library;

class Constants
{
    /** @const string */
    const USER_AGENT = 'UNT Digital Libraries Opensearch Client';

    /** @const string */
    const ATOM_MIME_TYPE = 'application/atom+xml';

    /** @const string */
    const PARAMETER_SEARCH_TERMS = 'searchTerms';

    /** @const string */
    const PARAMETER_COUNT = 'count';

    /** @const string */
    const PARAMETER_START_INDEX = 'startIndex';

    /** @const string */
    const PARAMETER_START_PAGE = 'startPage';

    /** @const string */
    const PARAMETER_LANGUAGE = 'language';

    /** @const string */
    const PARAMETER_INPUT_ENCODING = 'inputEncoding';

    /** @const string */
    const PARAMETER_OUTPUT_ENCODING = 'outputEncoding';

    /** @const string */
    const OPENSEARCH_NAMESPACE = 'http://a9.com/-/spec/opensearch/1.1/';

    /** @const string */
    const FORMAT = 'atom';

    /** @const string */
    const INI_APPEND = '=%s';

    /** @var array */
    public static $parameters = array(
        self::PARAMETER_SEARCH_TERMS,
        self::PARAMETER_COUNT,
        self::PARAMETER_START_INDEX,
        self::PARAMETER_START_PAGE,
        self::PARAMETER_LANGUAGE,
        self::PARAMETER_INPUT_ENCODING,
        self::PARAMETER_OUTPUT_ENCODING
    );

    /** @const string */
    const SIMPLEXML_EXCEPTION_MESSAGE = "The XML is not well formed, Unable to load it into a SimpleXMLElement Instance";

}
