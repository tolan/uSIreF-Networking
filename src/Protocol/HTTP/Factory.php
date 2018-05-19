<?php

namespace uSIreF\Networking\Protocol\HTTP;

use uSIreF\Networking\Interfaces\Protocol\{IFactory, IRequest, IResponse, IBuilder, IParser};

/**
 * This file defines class for creating HTTP protocol components
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Factory implements IFactory {

    /**
     * Creates protocol request.
     *
     * @return IRequest
     */
    public function createRequest(): IRequest {
        return new Request();
    }

    /**
     * Creates protocol response.
     *
     * @return IResponse
     */
    public function createResponse(): IResponse {
        return new Response();
    }

    /**
     * Creates protocol parser.
     *
     * @return IParser
     */
    public function createParser(): IParser {
        return new Parser();
    }

    /**
     * Creates protocol builder.
     *
     * @return IBuilder
     */
    public function createBuilder(): IBuilder {
        return new Builder();
    }

}