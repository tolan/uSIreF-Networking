<?php

namespace uSIreF\Networking\Protocol\HTTP\Request;

use uSIreF\Networking\Interfaces\Protocol\{IRequest, IResponse, IBuilder};
use uSIreF\Networking\Protocol\HTTP\Request;
use uSIreF\Networking\Protocol\Exception;

/**
 * This file defines class for build request message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Builder implements IBuilder {

    const HTTP_VERSION = 'HTTP/1.1';

    /**
     * Assigned request.
     *
     * @var Request
     */
    private $_request;

    /**
     * Count of written of rendered.
     *
     * @var integer
     */
    private $_written = 0;

    /**
     * Returns that the builder has completely read data.
     *
     * @return bool
     */
    public function isReadCompleted(): bool {
        return $this->data() !== null;
    }

    /**
     * Returns that the builder has completely written data.
     *
     * @return bool
     */
    public function isWriteCompleted(): bool {
        return $this->isReadCompleted() && strlen($this->render()) === 0;
    }

    /**
     * Render output message.
     *
     * @return string|null
     */
    public function render(): ?string {
        $message = $this->method().' '.$this->_buildUri().' '.self::HTTP_VERSION."\r\n".$this->_buildHeaders().$this->_buildBody();

        return substr($message, $this->written());
    }

    /**
     * Sets how many chars has written.
     *
     * @param int $count Count of written chars.
     *
     * @return int
     */
    public function written(int $count = null): int {
        $this->_written += (int)$count;

        return $this->_written;
    }

    /**
     * Gets or sets request to builder.
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
     * Gets or sets response to builder.
     *
     * @param IResponse $response Response instance
     *
     * @throws Exception
     *
     * @return IResponse|null
     */
    public function response(IResponse $response = null): ?IResponse {
        throw new Exception('This builder does not suuport response: '.get_class($response));
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
     * Build uri string with query.
     *
     * @return string
     */
    private function _buildUri(): string {
        $uri = '/'.ltrim($this->uri(), '/');
        return !empty($this->query()) ? $uri.'?'.http_build_query($this->query()) : $uri;
    }

    /**
     * Build request headers.
     *
     * @return string
     */
    private function _buildHeaders(): string {
        $result = '';
        $defaults = [
            'Host' => parse_url($this->requestUri(), PHP_URL_HOST),
        ];
        $headers  = array_merge($defaults, (array)$this->headers());
        foreach ($headers as $name => $value) {
            $result .= $name.': '.$value."\r\n";
        }

        return $result."\r\n";
    }

    /**
     * Build request body.
     *
     * @return string
     */
    private function _buildBody(): string {
        $result = $this->data();
        switch (true) {
            case is_array($result):
            case is_object($result):
                $result = http_build_query($result);
                break;
        }

        return (string)$result;
    }

}