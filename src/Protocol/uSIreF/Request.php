<?php

namespace uSIreF\Networking\Protocol\uSIreF;

use uSIreF\Networking\Interfaces\Protocol\IRequest;
use uSIreF\Networking\Traits\TData;

/**
 * This file defines class for custom uSIreF request.
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
     * Gets or sets request uri to request.
     *
     * @param string $uri Request uri
     *
     * @return string|null
     */
    public function requestUri(string $uri = null): ?string {
        return $this->handleData('requestUri', $uri);
    }

}