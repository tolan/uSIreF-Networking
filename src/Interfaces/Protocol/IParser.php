<?php

namespace uSIreF\Networking\Interfaces\Protocol;

/**
 * This file defines interface for parse protocol message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IParser {

    /**
     * Returns that the parser has completely read data.
     *
     * @return bool
     */
    public function isReadCompleted(): bool;

    /**
     * Adds data to parser for reading.
     *
     * @param string $data Received data.
     *
     * @return IParser
     */
    public function addData(string $data): IParser;

    /**
     * Gets or sets request to parser.
     *
     * @param IRequest $request Request instance
     *
     * @return IRequest|null
     */
    public function request(IRequest $request = null): ?IRequest;

    /**
     * Gets or sets response to parser.
     *
     * @param IResponse $response Response instance
     *
     * @return IResponse|null
     */
    public function response(IResponse $response = null): ?IResponse;

}