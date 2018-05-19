<?php

namespace uSIreF\Networking\Stream\Abstracts;

use uSIreF\Networking\Interfaces\Stream\IConnection;
use uSIreF\Networking\Stream\Exception;

/**
 * This file defines class for adapting connection, that is represented by socket connection resource.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
abstract class AConnection implements IConnection {

    /**
     * Socket resource.
     *
     * @var resource
     */
    private $_resource;

    /**
     * Construct method for inject resource pointer.
     *
     * @param resource $resource A resource pointer
     *
     * @return void
     */
    public function __construct($resource) {
        $this->_resource = $resource;
    }

    /**
     * Reads data from connection.
     *
     * @throws Exception
     *
     * @return string|null
     */
    public function read(): ?string {
        if (!$this->_resource) {
            throw new Exception('Connection is not readable.');
        }

        return fread($this->_resource, 30000);
    }

    /**
     * Writes data to connection and returns length of written.
     *
     * @param string $data Data to write
     *
     * @throws Exception
     *
     * @return int
     */
    public function write(string $data): int {
        if (!$this->_resource) {
            throw new Exception('Connection is not writeable.');
        }

        return fwrite($this->_resource, $data);
    }

    /**
     * Disconnects connection.
     *
     * @throws Exception
     *
     * @return bool
     */
    public function close(): bool {
        if (!$this->_resource) {
            throw new Exception('Connection is not closeable.');
        }

        fclose($this->_resource);
        $this->_resource = null;

        return true;
    }

}