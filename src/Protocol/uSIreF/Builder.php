<?php

namespace uSIreF\Networking\Protocol\uSIreF;

use uSIreF\Networking\Interfaces\Protocol\{IBuilder, IRequest, IResponse};

/**
 * This file defines class for build custom uSIreF protocol message.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Builder implements IBuilder {

    const SEPARATOR = '|';

    /**
     * Message entity.
     *
     * @var IRequest|IResponse
     */
    private $_entity;

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
        return $this->_entity->data() !== null;
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
        $stringed = (string)$this->_entity->data();
        $message  = strlen($stringed).self::SEPARATOR.$stringed;

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
     * @return IRequest|null
     */
    public function request(IRequest $request = null): ?IRequest {
        $this->_entity = $request;

        return $this->_entity;
    }

    /**
     * Gets or sets response to builder.
     *
     * @param IResponse $response Response instance
     *
     * @return IResponse|null
     */
    public function response(IResponse $response = null): ?IResponse {
        $this->_entity = $response;

        return $this->_entity;
    }

}