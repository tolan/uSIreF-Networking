<?php

namespace uSIreF\Networking\Protocol\HTTP;

use uSIreF\Networking\Interfaces\Protocol\IResponse;
use uSIreF\Networking\Traits\TData;

/**
 * This file defines class for HTTP response.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Response implements IResponse {

    use TData;

    /**
     * Construct method for set default values.
     *
     * @return void
     */
    public function __construct() {
        $this->handleData('code', Response\Code::OK_200);
    }

    /**
     * Gets or sets response code.
     *
     * @param int $code Response code
     *
     * @return int|null
     */
    public function code(int $code = null): ?int {
        return $this->handleData('code', $code);
    }

    /**
     * Gets or sets headers.
     *
     * @param array $headers Response headers
     *
     * @return array
     */
    public function headers(array $headers = null): ?array {
        return $this->handleData('headers', $headers);
    }

    /**
     * Gets or sets HTTP version.
     *
     * @param string $version HTTP version
     *
     * @return string
     */
    public function httpVersion(string $version = null): ?string {
        return $this->handleData('httpVersion', $version);
    }

    /**
     * Gets or sets response data.
     *
     * @param mixed $data Response data
     *
     * @return mixed|null
     */
    public function data($data = null) {
        return $this->handleData('data', $data);
    }

}