<?php

namespace uSIreF\Networking\Protocol\uSIreF;

use uSIreF\Networking\Interfaces\Protocol\{IRequest, IResponse, IParser};

/**
 * This file defines class for parse custom uSIreF protocol message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Parser implements IParser {

    const SEPARATOR = '|';

    /**
     * Message entity.
     *
     * @var IRequest|IResponse
     */
    private $_entity;

    /**
     * Data to parsing.
     *
     * @var string|null
     */
    private $_data;

    /**
     * Returns that the parser has completely read data.
     *
     * @return bool
     */
    public function isReadCompleted(): bool {
        $result = false;
        $parsed = $this->_getParsedData();
        if ($parsed !== null) {
            [$length, $message] = $parsed;
            $result             = strlen($message) >= $length;
        }

        return $result;
    }

    /**
     * Adds data to parser for reading.
     *
     * @param string $data Received data.
     *
     * @return IParser
     */
    public function addData(string $data): IParser {
        $this->_data .= $data;
        if ($this->isReadCompleted()) {
            $this->_entity->data($this->_getParsedData()[1]);
        }

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
        $this->_entity = $request;

        return $this->_entity;
    }

    /**
     * Gets or sets response to parser.
     *
     * @param IResponse $response Response instance
     *
     * @return IResponse|null
     */
    public function response(IResponse $response = null): ?IResponse {
        $this->_entity = $response;

        return $this->_entity;
    }

    /**
     * Returns current parsed data.
     *
     * @return array
     */
    private function _getParsedData(): ?array {
        return ($this->_data) ? explode(self::SEPARATOR, $this->_data, 2) : null;
    }

}