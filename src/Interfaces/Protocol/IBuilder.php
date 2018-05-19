<?php

namespace uSIreF\Networking\Interfaces\Protocol;

/**
 * This file defines interface for build protocol request or response.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IBuilder {

    /**
     * Returns that the builder has completely read data.
     *
     * @return bool
     */
    public function isReadCompleted(): bool;

    /**
     * Returns that the builder has completely written data.
     *
     * @return bool
     */
    public function isWriteCompleted(): bool;

    /**
     * Render output message.
     *
     * @return string|null
     */
    public function render(): ?string;

    /**
     * Sets how many chars has written.
     *
     * @param int $count Count of written chars.
     *
     * @return int
     */
    public function written(int $count = null): int;

    /**
     * Gets or sets request to builder.
     *
     * @param IRequest $request Request instance
     *
     * @return IRequest|null
     */
    public function request(IRequest $request = null): ?IRequest;

    /**
     * Gets or sets response to builder.
     *
     * @param IResponse $response Response instance
     *
     * @return IResponse|null
     */
    public function response(IResponse $response = null): ?IResponse;

}