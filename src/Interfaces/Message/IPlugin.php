<?php

namespace uSIreF\Networking\Interfaces\Message;

/**
 * This file defines interface for message plugin.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
interface IPlugin {

    /**
     * Updates plugin when observed message is changed.
     *
     * @param IMessage $message Message instance
     *
     * @return IMessage
     */
    public function update(IMessage $message): IMessage;

}