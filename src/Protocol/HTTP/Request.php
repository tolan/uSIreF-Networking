<?php

namespace uSIreF\Networking\Protocol\HTTP;

use uSIreF\Networking\Interfaces\Protocol\IRequest;
use uSIreF\Networking\Traits\TData;

/**
 * This file defines class for HTTP request.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Request implements IRequest {

    use TData;

    /**
     * Gets or sets data to request.
     *
     * @param mixed $data Request data
     *
     * @return mixed|null
     */
    public function data($data = null) {
        return $this->handleData('data', $data);
    }

    /**
     * Gets or sets method to request.
     *
     * @param string $method HTTP request code
     *
     * @return string|null
     */
    public function method(string $method = null): ?string {
        return $this->handleData('method', $method);
    }

    /**
     * Gets or sets request uri to request.
     *
     * @param string $uri Request uri
     *
     * @return string|null
     */
    public function requestUri(string $uri = null): ?string {
        return $this->handleData('requestUri', $uri);
    }

    /**
     * Gets or sets uri request.
     *
     * @param string $uri Uri
     *
     * @return string|null
     */
    public function uri(string $uri = null): ?string {
        return $this->handleData('uri', $uri);
    }

    /**
     * Gets or sets request HTTP version (default is 1.1).
     *
     * @param string $version HTTP version
     *
     * @return string|null
     */
    public function httpVersion(string $version = null): ?string {
        return $this->handleData('httpVersion', $version);
    }

    /**
     * Gets or sets query.
     *
     * @param array $query Request query params
     *
     * @return array
     */
    public function query(array $query = null): ?array {
        return $this->handleData('query', $query);
    }

    /**
     * Gets or sets headers.
     *
     * @param array $headers Request headers
     *
     * @return array
     */
    public function headers(array $headers = null): ?array {
        return $this->handleData('headers', $headers);
    }

}