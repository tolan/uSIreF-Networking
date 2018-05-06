<?php

namespace uSIreF\Networking\Unit\Tests\Traits;

/**
 * This file defines test class for trait TData. This is only for tests!
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class TData {

    use \uSIreF\Networking\Traits\TData;

    /**
     * Construct method for set default values.
     *
     * @return void
     */
    public function __construct() {
        $this->handleData('key', 'val');
    }

}