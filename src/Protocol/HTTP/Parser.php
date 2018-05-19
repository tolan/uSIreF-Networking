<?php

namespace uSIreF\Networking\Protocol\HTTP;

use uSIreF\Networking\Interfaces\Protocol\{IRequest, IResponse, IParser};

/**
 * This file defines class for parse HTTP protocol message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Parser implements IParser {

    /**
     * Specific Parser instance.
     *
     * @var IParser
     */
    private $_parser;

    /**
     * Returns that the parser has completely read data.
     *
     * @return bool
     */
    public function isReadCompleted(): bool {
        return $this->_parser->isReadCompleted();
    }

    /**
     * Adds data to parser for reading.
     *
     * @param string $data Received data.
     *
     * @return IParser
     */
    public function addData(string $data): IParser {
        $this->_parser->addData($data);
        return $this;
    }

    /**
     * Gets or sets request to parser.
     *
     * @param IRequest $request Request instance
     *
     * @return IRequest|null
     */
    public function request(IRequest $request = null): ?IRequest {
        $this->_parser = new Request\Parser();
        return $this->_parser->request($request);
    }

    /**
     * Gets or sets response to parser.
     *
     * @param IResponse $response Response instance
     *
     * @return IResponse|null
     */
    public function response(IResponse $response = null): ?IResponse {
        $this->_parser = new Response\Parser();
        return $this->_parser->response($response);
    }

}