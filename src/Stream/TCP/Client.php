<?php

namespace uSIreF\Networking\Stream\TCP;

use uSIreF\Networking\Stream\Abstracts\AClient;
use uSIreF\Networking\Interfaces\Message\{IFactory, IMessage};
use uSIreF\Networking\Interfaces\Protocol\IRequest;
use uSIreF\Networking\Stream\Exception;

/**
 * This file defines class for TCP stream client.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
class Client extends AClient {

    const PHP_URL_SCHEME   = 'scheme';
    const PHP_URL_HOST     = 'host';
    const PHP_URL_PORT     = 'port';
    const PHP_URL_USER     = 'user';
    const PHP_URL_PASS     = 'pass';
    const PHP_URL_PATH     = 'path';
    const PHP_URL_QUERY    = 'query';
    const PHP_URL_FRAGMENT = 'fragment';

    /**
     * Message factory.
     *
     * @var IFactory
     */
    private $_messageFactory;

    /**
     * Construct method for inject message factory.
     *
     * @param IFactory $factory Message factory instance
     *
     * @return void
     */
    public function __construct(IFactory $factory) {
        $this->_messageFactory = $factory;
    }

    /**
     * Returns server address.
     *
     * @param IRequest $request Protocol request
     *
     * @throws Exception
     *
     * @return string
     */
    protected function getAddress(IRequest $request): string {
        $uri    = $request->requestUri();
        $parsed = parse_url($uri);

        if (!$parsed) {
            throw new Exception('Request uri could not be resolved');
        }

        $scheme     = $this->_resolveScheme($parsed);
        $host       = $parsed[self::PHP_URL_HOST];
        $port       = $this->_resolvePort($parsed);
        $user       = ($parsed[self::PHP_URL_USER] ?? '');
        $pass       = ($parsed[self::PHP_URL_PASS]) ? ':'.$parsed[self::PHP_URL_PASS] : '';
        $credential = ($user || $pass) ? $user.$pass.'@' : '';
        $path       = ($parsed[self::PHP_URL_PATH] ?? '');
        $query      = ($parsed[self::PHP_URL_QUERY]) ? '?'.$parsed[self::PHP_URL_QUERY] : '';
        $fragment   = ($parsed[self::PHP_URL_FRAGMENT]) ? '#'.$parsed[self::PHP_URL_FRAGMENT] : '';

        return $scheme.$credential.$host.$port.$path.$query.$fragment;
    }

    /**
     * Creates outgoing message by request.
     *
     * @param resource $socket  Stream socket
     * @param IRequest $request Protocol request
     *
     * @return IMessage
     */
    protected function createMessage($socket, IRequest $request): IMessage {
        $message = $this->_messageFactory->createOutgoing(new Connection($socket));
        $message->getRequest()->from($request->to());

        return $message;
    }

    /**
     * Returns scheme (protocol).
     *
     * @param array $parsed Parsed uri
     *
     * @return string
     */
    private function _resolveScheme(array $parsed): string {
        $scheme = $parsed[self::PHP_URL_SCHEME];
        switch ($parsed[self::PHP_URL_SCHEME]) {
            case 'http':
            case null:
                $scheme = 'tcp';
                break;
        }

        return $scheme.'://';
    }

    /**
     * Returns port.
     *
     * @param array $parsed Parsed uri
     *
     * @throws Exception
     *
     * @return string
     */
    private function _resolvePort(array $parsed): string {
        $port = $parsed[self::PHP_URL_PORT];
        if (!$port) {
            switch ($parsed[self::PHP_URL_SCHEME]) {
                case 'http':
                    $port = 80;
                    break;
                default:
                    throw new Exception('Port could not be resolved.');
            }
        }

        return ':'.$port;
    }

}