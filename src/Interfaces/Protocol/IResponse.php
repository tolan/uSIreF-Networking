<?php

namespace uSIreF\Networking\Interfaces\Protocol;

/**
 * This file defines interface for protocol response.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IResponse {

    /**
     * Gets or sets response data.
     *
     * @param mixed $data Response data
     *
     * @return mixed|null
     */
    public function data($data = null);

}