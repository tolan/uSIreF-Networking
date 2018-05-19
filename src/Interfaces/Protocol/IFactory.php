<?php

namespace uSIreF\Networking\Interfaces\Protocol;

/**
 * This file defines interface for creating protocol components.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IFactory {

    /**
     * Creates protocol request.
     *
     * @return IRequest
     */
    public function createRequest(): IRequest;

    /**
     * Creates protocol response.
     *
     * @return IResponse
     */
    public function createResponse(): IResponse;

    /**
     * Creates protocol parser.
     *
     * @return IParser
     */
    public function createParser(): IParser;

    /**
     * Creates protocol builder.
     *
     * @return IBuilder
     */
    public function createBuilder(): IBuilder;

}