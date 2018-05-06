<?php

namespace uSIreF\Networking\Interfaces\Stream;

/**
 * This file defines interface for connection.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IConnection {

    /**
     * Reads data from connection.
     *
     * @return string|null
     */
    public function read(): ?string;

    /**
     * Writes data to connection and returns length of written.
     *
     * @param string $data Data to write
     *
     * @return int
     */
    public function write(string $data): int;

    /**
     * Disconnects connection.
     *
     * @return bool
     */
    public function close(): bool;

}