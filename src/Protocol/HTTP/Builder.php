<?php

namespace uSIreF\Networking\Protocol\HTTP;

use uSIreF\Networking\Interfaces\Protocol\{IBuilder, IRequest, IResponse};

/**
 * This file defines class for build HTTP protocol message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Builder implements IBuilder {

    /**
     * Specific Builder instance.
     *
     * @var IBuilder
     */
    private $_builder;

    /**
     * Returns that the builder has completely read data.
     *
     * @return bool
     */
    public function isReadCompleted(): bool {
        return $this->_builder->isReadCompleted();
    }

    /**
     * Returns that the builder has completely written data.
     *
     * @return bool
     */
    public function isWriteCompleted(): bool {
        return $this->_builder->isWriteCompleted();
    }

    /**
     * Render output message.
     *
     * @return string|null
     */
    public function render(): ?string {
        return $this->_builder->render();
    }

    /**
     * Sets how many chars has written.
     *
     * @param int $count Count of written chars.
     *
     * @return int
     */
    public function written(int $count = null): int {
        return $this->_builder->written($count);
    }

    /**
     * Gets or sets request to builder.
     *
     * @param IRequest $request Request instance
     *
     * @return IRequest|null
     */
    public function request(IRequest $request = null): ?IRequest {
        $this->_builder = new Request\Builder();

        return $this->_builder->request($request);
    }

    /**
     * Gets or sets response to builder.
     *
     * @param IResponse $response Response instance
     *
     * @return IResponse|null
     */
    public function response(IResponse $response = null): ?IResponse {
        $this->_builder = new Response\Builder();

        return $this->_builder->response($response);
    }

}