<?php

namespace uSIreF\Networking\Protocol\uSIreF;

use uSIreF\Networking\Interfaces\Protocol\IResponse;
use uSIreF\Networking\Traits\TData;

/**
 * This file defines class for custom uSIreF response.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Response implements IResponse {

    use TData;

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