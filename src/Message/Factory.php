<?php

namespace uSIreF\Networking\Message;

use uSIreF\Networking\Interfaces\Message\{IFactory, IMessage, IPlugin};
use uSIreF\Networking\Interfaces\Stream\IConnection;
use uSIreF\Networking\Interfaces\Protocol;

/**
 * This file defines class for creating messages.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Factory implements IFactory {

    /**
     * Protocol factory instance.
     *
     * @var IFactory
     */
    private $_factory;

    /**
     * Assigned plugins for each message.
     *
     * @var [IPlugin]
     */
    private $_plugins = [];

    /**
     * Construct method for inject protocol factory.
     *
     * @param Protocol\IFactory $factory Protocol factory instance
     *
     * @return void
     */
    public function __construct(Protocol\IFactory $factory) {
        $this->_factory = $factory;
    }

    /**
     * Adds plugin which will be assigned to each message.
     *
     * @param IPlugin $plugin Message plugin instance
     *
     * @return IFactory
     */
    public function addPlugin(IPlugin $plugin): IFactory {
        $this->_plugins[] = $plugin;

        return $this;
    }

    /**
     * Creates incomming message.
     *
     * @param IConnection $connection Stream connection instance
     *
     * @return IMessage
     */
    public function createIncomming(IConnection $connection): IMessage {
        $request  = $this->_factory->createRequest();
        $response = $this->_factory->createResponse();
        $parser   = $this->_factory->createParser();
        $builder  = $this->_factory->createBuilder();

        $parser->request($request);
        $builder->response($response);

        $message = new Incomming($connection, $request, $response, $parser, $builder);
        return $this->_addPlugins($message);
    }

    /**
     * Creates outgoing message.
     *
     * @param IConnection $connection Stream connection instance
     *
     * @return IMessage
     */
    public function createOutgoing(IConnection $connection): IMessage {
        $request  = $this->_factory->createRequest();
        $response = $this->_factory->createResponse();
        $parser   = $this->_factory->createParser();
        $builder  = $this->_factory->createBuilder();

        $builder->request($request);
        $parser->response($response);

        $message = new Outgoing($connection, $request, $response, $parser, $builder);
        return $this->_addPlugins($message);
    }

    /**
     * Adds all plugins to message.
     *
     * @param IMessage $message Message instance
     *
     * @return IMessage
     */
    private function _addPlugins(IMessage $message): IMessage {
        foreach ($this->_plugins as $plugin) {
            $message->addPlugin($plugin);
        }

        return $message;
    }

}