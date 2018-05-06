<?php

namespace uSIreF\Networking\Protocol\HTTP\Request;

use uSIreF\Networking\Interfaces\Protocol\{IRequest, IResponse, IParser};
use uSIreF\Networking\Protocol\HTTP\Request;
use uSIreF\Networking\Protocol\Exception;

/**
 * This file defines class for parsing HTTP request message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Parser implements IParser {

    /**
     * These constants define state of reading chunks.
     */
    const READ_CHUNK_HEADER  = 0;
    const READ_CHUNK_DATA    = 1;
    const READ_CHUNK_TRAILER = 2;

    /**
     * These constants define state of reading message.
     */
    const READ_HEADERS  = 0;
    const READ_CONTENT  = 1;
    const READ_COMPLETE = 2;

    /**
     * Assigned request.
     *
     * @var Request
     */
    private $_request;

    /**
     * Associative array of HTTP headers, with header names in lowercase.
     *
     * @var array
     */
    private $_lcHeaders = [];

    /**
     * Stream containing content of HTTP request (e.g. POST data).
     *
     * @var resource
     */
    private $_contentStream;

    /**
     * The HTTP request line exactly as it came from the client.
     *
     * @var string
     */
    private $_requestLine;

    /**
     * Reading state.
     *
     * @var string
     */
    private $_state = self::READ_HEADERS;

    /**
     * Header buffer for keeping data between reading.
     *
     * @var string
     */
    private $_headerBuffer = '';

    /**
     * Whole length of content.
     *
     * @var integer
     */
    private $_contentLength = 0;

    /**
     * Already read length of content.
     *
     * @var integer
     */
    private $_contentLengthRead = 0;

    /**
     * Flag for chunked message.
     *
     * @var boolean
     */
    private $_isChunked = false;

    /**
     * State of chunk.
     *
     * @var string
     */
    private $_chunkState = self::READ_CHUNK_HEADER;

    /**
     * Remaining length of chunk.
     *
     * @var integer
     */
    private $_chunkLengthRemaining = 0;

    /**
     * Remaining trail of chunk.
     *
     * @var integer
     */
    private $_chunkTrailerRemaining = 0;

    /**
     * Buffer for chunked header.
     *
     * @var string
     */
    private $_chunkHeaderBuffer = '';

    /**
     * Construct method for open content stream.
     *
     * @return void
     */
    public function __construct() {
        $this->_contentStream = fopen('data://text/plain,', 'r+b');
    }

    /**
     * Returns that the parser has completely read data.
     *
     * @return bool
     */
    public function isReadCompleted(): bool {
        return ($this->_state === self::READ_COMPLETE);
    }

    /**
     * Gets or sets request to parser.
     *
     * @param IRequest $request Request instance
     *
     * @return IRequest|null
     */
    public function request(IRequest $request = null): ?IRequest {
        $this->_request = $request;

        return $this->_request;
    }

    /**
     * Gets or sets response to parser.
     *
     * @param IResponse $response Response instance
     *
     * @throws Exception
     *
     * @return IResponse|null
     */
    public function response(IResponse $response = null): ?IResponse {
        throw new Exception('This parser does not suuport response: '.get_class($response));
    }

    /**
     * Gets or sets method to request.
     *
     * @param string $method HTTP request code
     *
     * @return string|null
     */
    public function method(string $method = null): ?string {
        return $this->_request->method($method);
    }

    /**
     * Gets or sets request uri to request.
     *
     * @param string $uri Request uri
     *
     * @return string|null
     */
    public function requestUri(string $uri = null): ?string {
        return $this->_request->requestUri($uri);
    }

    /**
     * Gets or sets uri request.
     *
     * @param string $uri Uri
     *
     * @return string|null
     */
    public function uri(string $uri = null): ?string {
        return $this->_request->uri($uri);
    }

    /**
     * Gets or sets request HTTP version (default is 1.1).
     *
     * @param string $version HTTP version
     *
     * @return string|null
     */
    public function httpVersion(string $version = null): ?string {
        return $this->_request->httpVersion($version);
    }

    /**
     * Gets or sets query.
     *
     * @param array $query Request query params
     *
     * @return array
     */
    public function query(array $query = null): ?array {
        return $this->_request->query($query);
    }

    /**
     * Gets or sets headers.
     *
     * @param array $headers Request headers
     *
     * @return array
     */
    public function headers(array $headers = null): ?array {
        return $this->_request->headers($headers);
    }

    /**
     * Gets or sets request data.
     *
     * @param mixed $data Request data
     *
     * @return mixed|null
     */
    public function data($data = null) {
        return $this->_request->data($data);
    }

    /**
     * Adds received data to current state and resolves that.
     *
     * @param string $data Received data
     *
     * @return Parser
     */
    public function addData(string $data): IParser {
        switch ($this->_state) {
            case self::READ_HEADERS:
                $this->_readHeaders($data);
                if ($this->_state !== self::READ_CONTENT) {
                    break;
                }

                // fallthrough to READ_CONTENT with leftover data
            case self::READ_CONTENT:
                if ($this->_isChunked) {
                    $this->_readChunkedData($data);
                } else {
                    $this->_readData($data);
                }
                break;
        }

        if ($this->_state == self::READ_COMPLETE) {
            fseek($this->_contentStream, 0);
        }

        return $this;
    }

    /**
     * Returns decoded header or null when it is not defined.
     *
     * @param string $name Header identificator
     *
     * @return string|null
     */
    public function getHeader(string $name): ?string {
        return ($this->_lcHeaders[strtolower($name)] ?? null);
    }

    /**
     * Clean up of current state.
     *
     * @return Parser
     */
    public function cleanup(): Parser {
        fclose($this->_contentStream);
        $this->_contentStream = null;
        $this->data('');

        return $this;
    }

    /**
     * Reads headers from received data.
     *
     * @param string $data Received data
     *
     * @return Parser
     */
    private function _readHeaders(string &$data): Parser {
        $this->_headerBuffer .= $data;
        $endHeaders           = strpos($this->_headerBuffer, "\r\n\r\n", 4);
        if ($endHeaders === false) {
            return $this;
        }

        // parse HTTP request line
        $endReq             = strpos($this->_headerBuffer, "\r\n");
        $this->_requestLine = substr($this->_headerBuffer, 0, $endReq);

        list($method, $requestUri, $httpVersion) = explode(' ', $this->_requestLine, 3);
        $this->method($method);
        $this->requestUri($requestUri);
        $this->httpVersion($httpVersion);

        $parsedUri = parse_url($requestUri);
        $this->uri(urldecode($parsedUri['path']));

        $query = [];
        parse_str(($parsedUri['query'] ?? ''), $query);
        $this->query($query);

        // parse HTTP headers
        $startHeaders  = ($endReq + 2);
        $headersStr    = substr($this->_headerBuffer, $startHeaders, ($endHeaders - $startHeaders));
        $this->headers($this->_readHeaderString($headersStr));

        $this->_lcHeaders = [];
        foreach ($this->headers() as $key => $value) {
            $this->_lcHeaders[strtolower($key)] = $value;
        }

        if (isset($this->_lcHeaders['transfer-encoding'])) {
            $this->_isChunked     = $this->_lcHeaders['transfer-encoding'] === 'chunked';
            $this->_contentLength = 0;
            unset($this->_lcHeaders['transfer-encoding']);
            $headers = $this->headers();
            unset($headers['Transfer-Encoding']);
            $this->headers($headers);
        } else {
            $this->_contentLength = (int)($this->_lcHeaders['content-length'] ?? 0);
        }

        // $endHeaders is before last \r\n\r\n
        $startContent        = ($endHeaders + 4);
        $data                = substr($this->_headerBuffer, $startContent);
        $this->_headerBuffer = '';
        $this->_state        = self::READ_CONTENT;

        return $this;
    }

    /**
     * Reads chunked data from received data.
     *
     * @param string $data Received data
     *
     * @return Parser
     */
    private function _readChunkedData(string &$data): Parser {
        // keep processing chunks until we run out of data
        while (isset($data[0])) {
            switch ($this->_chunkState) {
                case self::READ_CHUNK_HEADER:
                    $this->_chunkHeaderBuffer .= $data;
                    $data                      = '';
                    $endChunkHeader            = strpos($this->_chunkHeaderBuffer, "\r\n");
                    // still need to read more chunk header
                    if ($endChunkHeader === false) {
                        break;
                    }

                    // done with chunk header
                    $chunkHeader       = substr($this->_chunkHeaderBuffer, 0, $endChunkHeader);
                    list($chunkLenHex) = explode(';', $chunkHeader, 2);

                    $this->_chunkLengthRemaining = intval($chunkLenHex, 16);
                    $this->_chunkState           = self::READ_CHUNK_DATA;
                    $data                        = substr($this->_chunkHeaderBuffer, ($endChunkHeader + 2));
                    $this->_chunkHeaderBuffer    = '';

                    if ($this->_chunkLengthRemaining == 0) {
                        $this->_state = self::READ_COMPLETE;

                        $headers = $this->headers();
                        $headers['Content-Length'] = $this->_contentLength;
                        $this->headers($headers);
                        $this->_lcHeaders['content-length'] = $this->_contentLength;

                        // TODO: this is where we should process trailers...
                        return $this;
                    }

                    // fallthrough to READ_CHUNK_DATA with leftover data
                case self::READ_CHUNK_DATA:
                    if (strlen($data) > $this->_chunkLengthRemaining) {
                        $chunkData = substr($data, 0, $this->_chunkLengthRemaining);
                    } else {
                        $chunkData = $data;
                    }

                    $this->_contentLength += strlen($chunkData);
                    fwrite($this->_contentStream, $chunkData);
                    $data                         = substr($data, $this->_chunkLengthRemaining);
                    $this->_chunkLengthRemaining -= strlen($chunkData);

                    if ($this->_chunkLengthRemaining == 0) {
                        $this->_chunkTrailerRemaining = 2;
                        $this->_chunkState = self::READ_CHUNK_TRAILER;
                    }
                    break;
                // each chunk ends in \r\n, which we ignore
                case self::READ_CHUNK_TRAILER:
                    $lenToRead                     = min(strlen($data), $this->_chunkTrailerRemaining);
                    $data                          = substr($data, $lenToRead);
                    $this->_chunkTrailerRemaining -= $lenToRead;

                    if ($this->_chunkTrailerRemaining == 0) {
                        $this->_chunkState = self::READ_CHUNK_HEADER;
                    }
                    break;
            }
        }

        return $this;
    }

    /**
     * Reads non-chunked data from received data.
     *
     * @param string $data Received data
     *
     * @return Parser
     */
    private function _readData(&$data): Parser {
        fwrite($this->_contentStream, $data);
        $this->_contentLengthRead += strlen($data);
        $this->data($this->data().$data);

        if (($this->_contentLength - $this->_contentLengthRead) <= 0) {
            $this->_state = self::READ_COMPLETE;
        }

        return $this;
    }

    /**
     * Reads headers from received headers string.
     *
     * @param string $headersStr Headers string
     *
     * @return array
     */
    private function _readHeaderString(string $headersStr): array {
        $headersArr = explode("\r\n", $headersStr);
        $headers    = [];
        foreach ($headersArr as $headerStr) {
            $headerArr = explode(': ', $headerStr, 2);
            if (count($headerArr) === 2) {
                $headers[$headerArr[0]] = $headerArr[1];
            }
        }

        return $headers;
    }

}