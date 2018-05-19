<?php

include __DIR__.'/../../vendor/autoload.php';

const LOG_DIR = '/app';

use uSIreF\Networking;

function runServer() {
    $protocolFactory = new Networking\Protocol\uSIreF\Factory();
    $messageFactory  = new Networking\Message\Factory($protocolFactory);
    $messageFactory->addPlugin(new Networking\Message\Plugins\OK());

    $stream = new Networking\Stream\UNIX\Server(__DIR__.'/socket', $messageFactory);
    $server = new Networking\Server($stream);

    try {
        $server->run();
    } catch (\Throwable $e) {
        echo 'ERROR: '.$e->getMessage()."\n";
        echo 'Stack: '.$e->getTraceAsString()."\n\n";
        echo 'Others: '.print_r($server->flushErrors(), true)."\n";
        throw $e;
    }
}

echo "START Server\n";
runServer();
echo "STOP Server\n";