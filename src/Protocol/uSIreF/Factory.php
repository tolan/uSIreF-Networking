<?php

namespace uSIreF\Networking\Protocol\uSIreF;

use uSIreF\Networking\Interfaces\Protocol\{IFactory, IRequest, IResponse, IParser, IBuilder};

/**
 * This file defines class for creating custom uSIreF protocol components.
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