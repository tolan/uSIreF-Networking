<?php

namespace uSIreF\Networking\Stream\Utils;

/**
 * This file defines class for stream timeout utilities.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Timeout {

    /**
     * Returns computed timeout in seconds and microsecond from miliseconds.
     *
     * @param float $timeout Timeout in ms
     *
     * @return array
     */
    public static function toArray(float $timeout = null): array {
        $sec  = null;
        $usec = null;

        if ($timeout) {
            $sec     = floor($timeout / 1000);
            $timeout = ($timeout - ($sec * 1000));
            $usec    = round(($timeout * 1000), 0);
        }

        return [$sec, $usec];
    }

}