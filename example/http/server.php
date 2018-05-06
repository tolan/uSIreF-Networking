<?php

include __DIR__.'/../../vendor/autoload.php';

use uSIreF\Networking;

function runServer() {
    $host = '0.0.0.0';
    $port = 80;

    $protocolFactory = new Networking\Protocol\HTTP\Factory();
    $messageFactory  = new Networking\Message\Factory($protocolFactory);
    $messageFactory->addPlugin(new Networking\Message\Plugins\OK());

    $stream = new Networking\Stream\TCP\Server($host, $port, $messageFactory);
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