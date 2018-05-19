<?php

namespace uSIreF\Networking\Interfaces\Protocol;

/**
 * This file defines interface for protocol request.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IRequest {

    /**
     * Gets or sets data to request.
     *
     * @param mixed $data Request data
     *
     * @return mixed|null
     */
    public function data($data = null);

    /**
     * Gets or sets request uri (it is complete target uri)
     *
     * @param string $uri Request uri (complete target uri)
     *
     * @return string|null
     */
    public function requestUri(string $uri = null): ?string;

}