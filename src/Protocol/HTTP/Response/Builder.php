<?php

namespace uSIreF\Networking\Protocol\HTTP\Response;

use uSIreF\Networking\Interfaces\Protocol\{IRequest, IResponse, IBuilder};
use uSIreF\Networking\Protocol\HTTP\Response;
use uSIreF\Networking\Protocol\Exception;

/**
 * This file defines class for build response message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Builder implements IBuilder {

    /**
     * Assigned response.
     *
     * @var Response
     */
    private $_response;

    /**
     * Count of written of rendered.
     *
     * @var integer
     */
    private $_written = 0;

    /**
     * Returns that the builder has completely read data.
     *
     * @return bool
     */
    public function isReadCompleted(): bool {
        return $this->data() !== null;
    }

    /**
     * Returns that the builder has completely written data.
     *
     * @return bool
     */
    public function isWriteCompleted(): bool {
        return $this->isReadCompleted() && strlen($this->render()) === 0;
    }

    /**
     * Render output message.
     *
     * @return string|null
     */
    public function render(): ?string {
        $data     = $this->_renderData();
        $defaults = [
            'Content-Length' => strlen($data),
        ];

        $headers  = array_merge($defaults, (array)$this->headers());
        $message  = Code::renderStatus($this->code());
        $message .= $this->_renderHeaders($headers);
        $message .= $data;

        return substr($message, $this->written());
    }

    /**
     * Sets how many chars has written.
     *
     * @param int $count Count of written chars.
     *
     * @return int
     */
    public function written(int $count = null): int {
        $this->_written += (int)$count;

        return $this->_written;
    }

    /**
     * Gets or sets request to builder.
     *
     * @param IRequest $request Request instance
     *
     * @throws Exception
     *
     * @return IRequest|null
     */
    public function request(IRequest $request = null): ?IRequest {
        throw new Exception('This builder does not suuport request: '.get_class($request));
    }

    /**
     * Gets or sets response to builder.
     *
     * @param IResponse $response Response instance
     *
     * @return IResponse|null
     */
    public function response(IResponse $response = null): ?IResponse {
        $this->_response = $response;

        return $this->_response;
    }

    /**
     * Gets or sets response code.
     *
     * @param int $code Response code
     *
     * @return int|null
     */
    public function code(int $code = null): ?int {
        return $this->_response->code($code);
    }

    /**
     * Gets or sets headers.
     *
     * @param array $headers Request headers
     *
     * @return array
     */
    public function headers(array $headers = null): ?array {
        return $this->_response->headers($headers);
    }

    /**
     * Gets or sets request data.
     *
     * @param mixed $data Request data
     *
     * @return mixed|null
     */
    public function data($data = null) {
        return $this->_response->data($data);
    }

    /**
     * Returns rendered header string.
     *
     * @param array $headers Headers data
     *
     * @return string
     */
    private function _renderHeaders(array $headers = []): string {
        $result = '';
        foreach ($headers as $name => $value) {
            $result .= $name.': '.$value."\r\n";
        }

        $result .= "\r\n";

        return $result;
    }

    /**
     * Returns rendered data string.
     *
     * @return string
     */
    private function _renderData(): string {
        $data   = $this->data();
        $result = (string)$data;

        if (is_array($data)) {
            $result = http_build_query($data);
        }

        return $result;
    }

}